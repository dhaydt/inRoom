<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Apply;
use App\Model\Category;
use App\Model\Fasilitas;
use App\Model\Jobs;
use App\Model\Kampus;
use App\Model\Rule;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type)
    {
        $query_param = [];
        $search = $request['search'];
        if ($type == 'in_house') {
            $products = Jobs::with('seller')->where(['added_by' => 'admin']);
        } else {
            $products = Jobs::with('seller')->where('added_by', 'seller');
        }

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $products = $products->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }

        $request_status = $request['status'];
        $products = $products->orderBy('id', 'DESC')->paginate(Helpers::pagination_limit())->appends(['status' => $request['status']])->appends($query_param);

        return view('admin-views.jobs.list', compact('products', 'search'));
    }

    public function details_applied($id)
    {
        $order = Apply::with('job', 'customer')->where(['id' => $id])->first();
        if ($order->job_status == 'applied') {
            $order->job_status = 'viewed';
            $order->save();
        }
        $order = Apply::with('job', 'customer')->where(['id' => $id])->first();

        return view('admin-views.jobs.job-details', compact('order'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function approve_status(Request $request)
    {
        $product = Jobs::find($request->id);
        $product->request_status = 1;
        $product->save();

        return redirect()->route('admin.product.list', ['seller', 'status' => $product['request_status']]);
    }

    public function applied(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        // if (session()->has('show_inhouse_orders') && session('show_inhouse_orders') == 1) {
        //     $query = Order::whereHas('details', function ($query) {
        //         $query->whereHas('product', function ($query) {
        //             $query->where('added_by', 'admin');
        //         });
        //     })->with(['customer', 'details']);

        //     if ($status != 'all') {
        //         $orders = $query->where(['order_status' => $status]);
        //     } else {
        //         $orders = $query;
        //     }

        //     if ($request->has('search')) {
        //         $key = explode(' ', $request['search']);
        //         $orders = $orders->where(function ($q) use ($key) {
        //             foreach ($key as $value) {
        //                 $q->orWhere('id', 'like', "%{$value}%")
        //                     ->orWhere('order_status', 'like', "%{$value}%")
        //                     ->orWhere('transaction_ref', 'like', "%{$value}%");
        //             }
        //         });
        //         $query_param = ['search' => $request['search']];
        //     }
        // } else {
        //     if ($status != 'all') {
        //         $orders = Order::with(['customer', 'details'])->where(['order_status' => $status]);
        //     } else {
        //         $orders = Order::with(['customer', 'details']);
        //     }

        //     if ($request->has('search')) {
        //         $key = explode(' ', $request['search']);
        //         $orders = $orders->where(function ($q) use ($key) {
        //             foreach ($key as $value) {
        //                 $q->orWhere('id', 'like', "%{$value}%")
        //                     ->orWhere('order_status', 'like', "%{$value}%")
        //                     ->orWhere('transaction_ref', 'like', "%{$value}%");
        //             }
        //         });
        //         $query_param = ['search' => $request['search']];
        //     }
        // }
        $orders = Apply::with('job')->orderBy('id', 'DESC');

        $orders = $orders->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.jobs.apply', compact('orders', 'search'));
    }

    public function apply_status(Request $request)
    {
        $order = Apply::where('id', $request->id)->first();
        // dd($order);
        // $fcm_token = $order->customer->cm_firebase_token;
        // $value = Helpers::order_status_update_message($request->order_status);
        // try {
        //     if ($value) {
        //         $data = [
        //             'title' => translate('Order'),
        //             'description' => $value,
        //             'order_id' => $order['id'],
        //             'image' => '',
        //         ];
        //         Helpers::send_push_notif_to_device($fcm_token, $data);
        //     }
        // } catch (\Exception $e) {
        // }

        $order->job_status = $request->order_status;
        // OrderManager::stock_update_on_order_status_change($order, $request->order_status);
        $order->save();
        // $transaction = OrderTransaction::where(['order_id' => $order['id']])->first();
        // if (isset($transaction) && $transaction['status'] == 'disburse') {
        //     return response()->json($request->order_status);
        // }

        // if ($request->order_status == 'delivered' && $order['seller_id'] != null) {
        //     OrderManager::wallet_manage_on_order_status_change($order, 'admin');
        // }
        Toastr::success('Kandidat berhasil diterima');

        return redirect()->back();
    }

    public function status_update(Request $request)
    {
        $product = Jobs::where(['id' => $request['id']])->first();
        $success = 1;
        if ($request['status'] == 1) {
            if ($product->added_by == 'seller' && $product->request_status == 0) {
                $success = 0;
            } else {
                $product->status = $request['status'];
            }
        } else {
            $product->status = $request['status'];
        }
        $product->save();

        return response()->json([
            'success' => $success,
        ], 200);
    }

    public function create()
    {
        $rule = Rule::get();
        $fas = Fasilitas::where('tipe', 'umum')->get();
        $cat = Category::get();
        $ptn = Kampus::get();

        return view('admin-views.jobs.add-new', compact('ptn', 'fas', 'rule', 'cat'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $auth = 0;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'company_name' => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'logo' => 'required',
            'penempatan' => 'required',
            'keahlian' => 'required',
            'pendidikan' => 'required',
            'status' => 'required',
            'deskripsi' => 'required',
            'gaji' => 'required',
            'satuan' => 'required',
        ], [
            'name.required' => 'Nama pekerjaan diperlukan!',
            'company_name.required' => 'Nama perusahaan diperlukan!',
            'province.required' => 'Mohon provinsi nya di isi!',
            'city.required' => 'Mohon kota nya di isi!',
            'district.required' => 'Mohon kecamatannya nya di isi!',
            'deskripsi.required' => 'Mohon isi deskripsi pekerjaan!',
            'status.required' => 'Mohon isi status pekerjaan!',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $img = ImageManager::upload('jobs/', 'png', $request->file('logo'));
        $prov = Province::where('id', $request['province'])->first();
        $city = City::where('id', $request['city'])->first();

        $kost = new Jobs();
        $kost->company_name = $request['company_name'];
        $kost->province = $prov->name;
        $kost->city = $city->name;
        $kost->district = $request['district'];
        $kost->note_address = $request['noteAddress'];
        $kost->penempatan = $request['penempatan'];
        $kost->onsite = $request['onsite'];
        $kost->name = $request['name'];
        $kost->keahlian = $request['keahlian'];
        $kost->pendidikan = $request['pendidikan'];
        $kost->status_employe = $request['status'];
        $kost->description = $request['deskripsi'];
        $kost->gaji = $request['gaji'];
        $kost->hide_gaji = $request['hide'];
        $kost->satuan_gaji = $request['satuan'];
        $kost->logo = $img;
        $kost->seller_id = $auth;
        $kost->added_by = 'admin';

        $kost->penanggung_jwb = $request['penanggung'];
        $kost->hp_penanggung_jwb = $request['hp'];
        $kost->email_penanggung_jwb = $request['email'];
        $kost->expire = $request['expire'];
        $kost->status = 0;
        $kost->request_status = 1;
        $kost->slug = Str::slug($request['name'], '-').'-'.Str::random(6);
        if ($request->ajax()) {
            return response()->json([], 200);
        } else {
            $kost->save();

            Toastr::success('Pekerjaan berhasil ditambahkan!');

            return redirect()->route('admin.jobs.list', ['type' => 'in_house']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Jobs::find($id);
        $rules = Rule::get();
        $fas = Fasilitas::where('tipe', 'umum')->get();
        $cat = Category::get();
        $ptn = Kampus::get();

        $rule = json_decode($product->aturan_id);
        $fasilitas = json_decode($product->fasilitas_id);

        return view('admin-views.jobs.edit', compact('ptn', 'product', 'rule', 'fasilitas', 'rules', 'fas', 'cat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'company_name' => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'penempatan' => 'required',
            'keahlian' => 'required',
            'pendidikan' => 'required',
            'status' => 'required',
            'deskripsi' => 'required',
            'gaji' => 'required',
            'satuan' => 'required',
        ], [
            'name.required' => 'Nama pekerjaan diperlukan!',
            'company_name.required' => 'Nama perusahaan diperlukan!',
            'province.required' => 'Mohon provinsi nya di isi!',
            'city.required' => 'Mohon kota nya di isi!',
            'district.required' => 'Mohon kecamatannya nya di isi!',
            'deskripsi.required' => 'Mohon isi deskripsi pekerjaan!',
            'status.required' => 'Mohon isi status pekerjaan!',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $prov = Province::where('id', $request['province'])->first();
        $city = City::where('id', $request['city'])->first();

        $product = Jobs::find($id);
        $product->company_name = $request['company_name'];
        $product->province = $prov->name;
        $product->city = $city->name;
        $product->district = $request['district'];
        $product->note_address = $request['noteAddress'];
        $product->penempatan = $request['penempatan'];
        $product->onsite = $request['onsite'];
        $product->name = $request['name'];
        $product->keahlian = $request['keahlian'];
        $product->pendidikan = $request['pendidikan'];
        $product->status_employe = $request['status'];
        $product->description = $request['deskripsi'];
        $product->gaji = $request['gaji'];
        $product->hide_gaji = $request['hide'];
        $product->satuan_gaji = $request['satuan'];
        // $product->logo = $img;
        // $product->seller_id = $auth;
        // $product->added_by = 'admin';

        $product->penanggung_jwb = $request['penanggung'];
        $product->hp_penanggung_jwb = $request['hp'];
        $product->email_penanggung_jwb = $request['email'];
        $product->expire = $request['expire'];

        if ($request->ajax()) {
            return response()->json([], 200);
        } else {
            $product->logo = $request->file('logo') ? ImageManager::update('jobs/', $product->logo, 'png', $request->file('logo')) : $product->logo;
            $product->save();
            Toastr::success('Pekerjaan berhasil diupdate.');

            return redirect()->route('admin.jobs.list', ['type' => 'in_house']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Jobs::find($id);
        if (!isset($product)) {
            return back();
        }

        if (isset($product->logo)) {
            ImageManager::delete('/jobs/'.$product->logo);
        }

        $product->delete();
        Toastr::success('Pekerjaan berhasil dihapus!');

        return back();
    }
}
