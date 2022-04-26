<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\CustomerManager;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Apply;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\ShippingAddress;
use App\Model\SupportTicket;
use App\Model\SupportTicketConv;
use App\Model\Wishlist;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function info(Request $request)
    {
        return response()->json($request->user(), 200);
    }

    public function create_support_ticket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'type' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $request['customer_id'] = $request->user()->id;
        $request['priority'] = 'low';
        $request['status'] = 'pending';

        try {
            CustomerManager::create_support_ticket($request);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => [
                    'code' => 'failed',
                    'message' => 'Something went wrong',
                ],
            ], 422);
        }

        return response()->json(['message' => 'Support ticket created successfully.'], 200);
    }

    public function reply_support_ticket(Request $request, $ticket_id)
    {
        $support = new SupportTicketConv();
        $support->support_ticket_id = $ticket_id;
        $support->admin_id = 1;
        $support->customer_message = $request['message'];
        $support->save();

        return response()->json(['message' => 'Support ticket reply sent.'], 200);
    }

    public function get_support_tickets(Request $request)
    {
        return response()->json(SupportTicket::where('customer_id', $request->user()->id)->get(), 200);
    }

    public function get_support_ticket_conv($ticket_id)
    {
        return response()->json(SupportTicketConv::where('support_ticket_id', $ticket_id)->get(), 200);
    }

    public function add_to_wishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $wishlist = Wishlist::where('customer_id', $request->user()->id)->where('product_id', $request->product_id)->first();

        if (empty($wishlist)) {
            $wishlist = new Wishlist();
            $wishlist->customer_id = $request->user()->id;
            $wishlist->product_id = $request->product_id;
            $wishlist->save();

            return response()->json(['message' => translate('successfully added!')], 200);
        }

        return response()->json(['message' => translate('Already in your wishlist')], 200);
    }

    public function remove_from_wishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $wishlist = Wishlist::where('customer_id', $request->user()->id)->where('product_id', $request->product_id)->first();

        if (!empty($wishlist)) {
            Wishlist::where(['customer_id' => $request->user()->id, 'product_id' => $request->product_id])->delete();

            return response()->json(['message' => translate('successfully removed!')], 200);
        }

        return response()->json(['message' => translate('No such data found!')], 404);
    }

    public function wish_list(Request $request)
    {
        return response()->json(Wishlist::whereHas('product')->where('customer_id', $request->user()->id)->get(), 200);
    }

    public function address_list(Request $request)
    {
        return response()->json(ShippingAddress::where('customer_id', $request->user()->id)->get(), 200);
    }

    public function add_new_address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_person_name' => 'required',
            'address_type' => 'required',
            'address' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $address = [
            'customer_id' => $request->user()->id,
            'contact_person_name' => $request->name,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'city' => $request->city,
            'zip' => $request->zip,
            'phone' => $request->phone,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('shipping_addresses')->insert($address);

        return response()->json(['message' => translate('successfully added!')], 200);
    }

    public function delete_address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if (DB::table('shipping_addresses')->where(['id' => $request['address_id'], 'customer_id' => $request->user()->id])->first()) {
            DB::table('shipping_addresses')->where(['id' => $request['address_id'], 'customer_id' => $request->user()->id])->delete();

            return response()->json(['message' => 'successfully removed!'], 200);
        }

        return response()->json(['message' => translate('No such data found!')], 404);
    }

    public function listLamaran(Request $request)
    {
        $apply = Apply::where(['customer_id' => $request->user()->id])->get();

        return response()->json($apply, 200);
    }

    public function get_order_list(Request $request)
    {
        $orders = Order::with('details')->where(['customer_id' => $request->user()->id])->get();
        $data = $orders->map(function ($data) {
            $product = json_decode($data['details'][0]->product_details);
            $penyewa = json_decode($data['details'][0]->data_penyewa);
            $fasilitas_id = json_decode($product->fasilitas_id);
            $fasilitas = [];
            foreach ($fasilitas_id as $f) {
                $name = Helpers::fasilitas($f);
                array_push($fasilitas, $name);
            }
            $item = [
                'id' => $data['id'],
                'customer_id' => $data['customer_id'],
                'customer_type' => $data['customer_type'],
                'payment_status' => $data['payment_status'],
                'struk' => $data['struk'],
                'alasan_user' => $data['alasan_user'],
                'alasan_admin' => $data['alasan_admin'],
                'order_status' => $data['order_status'],
                'room_name' => Helpers::roomName($data['roomDetail_id']),
                'mulai' => $data['mulai'],
                'durasi' => $data['durasi'],
                'catatan_tambahan' => $data['catatan_tambahan'],
                'ktp' => $data['ktp'],
                'jumlah_penyewa' => $data['jumlah_penyewa'],
                'auto_cancel' => $data['auto_cancel'],
                'transaction_ref' => $data['transaction_ref'],
                'order_amount' => $data['order_amount'],
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],
                'discount_amount' => $data['discount_amount'],
                'discount_type' => $data['discount_type'],
                'coupon_code' => $data['coupon_code'],
                'order_group_id' => $data['order_group_id'],
                'verification_code' => $data['verification_code'],
                'seller_id' => $data['seller_id'],
                'seller_is' => $data['seller_is'],
                'product_id' => $product->id,
                'product_type' => $product->type,
                'fasilitas_kamar' => $fasilitas,
                'product_image' => json_decode($product->images)[0],
                'product_district' => $product->kost->district,
                'product_city' => $product->kost->city,
                'product_province' => $product->kost->province,
                'nama_penyewa' => $penyewa->f_name.' '.$penyewa->l_name,
                'hp_penyewa' => $penyewa->phone,
            ];

            return $item;
        });

        return response()->json($data, 200);
    }

    public function get_order_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $details = OrderDetail::where(['order_id' => $request['order_id']])->get();
        $details->map(function ($query) {
            $query['variation'] = json_decode($query['variation'], true);
            $query['product_details'] = Helpers::product_data_formatting(json_decode($query['product_details'], true));

            return $query;
        });

        return response()->json($details, 200);
    }

    public function update_profile(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'kelamin' => 'required',
            'asal' => 'required',
            'tgl_lahir' => 'required',
        ], [
            'kelamin.required' => 'Mohon pilih jenis kelamin',
            'asal.required' => 'Mohon pilih kota asal',
            'tgl_lahir.required' => 'Mohon tanggal lahir diisi',
        ]);
        if ($request->tgl_lahir == null) {
            return response()->json(['errors' => 'Tanggal lahir mohon diisi'], 403);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if ($request->has('image')) {
            $imageName = ImageManager::update('profile/', $request->user()->image, 'png', $request->file('image'));
        } else {
            $imageName = $request->user()->image;
        }

        $image = $request->file('ktp');

        if ($image != null) {
            $ktp = ImageManager::update('ktp/', $request->user()->ktp, 'png', $request->file('ktp'));
        } else {
            $ktp = $request->user()->image;
        }

        if ($request['password'] != null && strlen($request['password']) > 5) {
            $pass = bcrypt($request['password']);
        } else {
            $pass = $request->user()->password;
        }

        $userDetails = [
            'f_name' => $request->f_name ? $request->f_name : $request->user()->f_name,
            'l_name' => $request->l_name ? $request->l_name : $request->user()->l_name,
            'phone' => $request->phone ? $request->phone : $request->user()->phone,
            'kelamin' => $request->kelamin,
            'asal' => $request->asal,
            'lahir' => $request->tgl_lahir,
            'image' => $imageName,
            'ktp' => $ktp,
            'password' => $pass,
            'status_pernikahan' => $request->status_pernikahan,
            'pendidikan' => $request->pendidikan,
            'hp_darurat' => $request->hp_darurat,
            'pekerjaan' => $request->pekerjaan,
            'kampus' => $request->kampus,
            'tempat_kerja' => $request->tempat_kerja,
            'updated_at' => now(),
        ];

        User::where(['id' => $request->user()->id])->update($userDetails);

        return response()->json(['message' => translate('successfully updated!')], 200);
    }

    public function update_cm_firebase_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cm_firebase_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        DB::table('users')->where('id', $request->user()->id)->update([
            'cm_firebase_token' => $request['cm_firebase_token'],
        ]);

        return response()->json(['message' => translate('successfully updated!')], 200);
    }
}
