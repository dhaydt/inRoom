<?php

namespace App\Http\Controllers\Seller;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\OrderManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\Seller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function list(Request $request, $status)
    {
        $sellerId = auth('seller')->id();
        if ($status != 'all') {
            $orders = Order::where(['seller_is' => 'seller'])->where(['seller_id' => $sellerId])->where(['order_status' => $status]);
        } else {
            $orders = Order::where(['seller_is' => 'seller'])->where(['seller_id' => $sellerId]);
        }

        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $orders = $orders->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('id', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }
        //dd($orders->count())
        $orders = $orders->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('seller-views.order.list', compact('orders', 'search'));
    }

    public function manual_upload(Request $request)
    {
        // if ($request->no_kamar == null) {
        //     Toastr::warning('Mohon pilih no kamar yang akan ditempati!');

        //     return redirect()->back();
        // }
        // dd($request);
        if ($request->file('image') != null) {
            $order = Order::find($request->id);
            $fcm_token = $order->customer->cm_firebase_token;
            $value = Helpers::order_status_update_message($request->order_status);
            try {
                if ($value) {
                    $data = [
                        'title' => translate('Order'),
                        'description' => $value,
                        'order_id' => $order['id'],
                        'image' => '',
                    ];
                    Helpers::send_push_notif_to_device($fcm_token, $data);
                }
            } catch (\Exception $e) {
            }

            $kamar = $order->roomDetail_id;
            if (strpos($kamar, 'id') !== false) {
                $rom = 'ditempat';
            } else {
                $rom = $kamar;
            }
            $status = $request->order_status;

            $img = $request->file('image');
            $struk = ImageManager::upload('struk/', 'png', $img);
            $order->struk = $struk;

            $order->order_status = $status;
            $order->payment_status = 'paid';
            $order->roomDetail_id = $rom;
            // $uid = $order->customer_id;
            // dd($rom);
            // OrderManager::updateRoom($kamar, 0, $uid);
            $order->save();

            Toastr::success('Bukti transfer berhasil di upload');

            return redirect()->back();
        }
        Toastr::warning('Mohon pilih bukti transfer!!!!');

        return redirect()->back();
    }

    public function details($id)
    {
        $sellerId = auth('seller')->id();
        $order = Order::with(['details' => function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        }])->with('customer', 'shipping')
            ->where('id', $id)->first();

        return view('seller-views.order.order-details', compact('order'));
    }

    public function generate_invoice($id)
    {
        $sellerId = auth('seller')->id();
        $seller = Seller::findOrFail($sellerId)->gst;

        $order = Order::with(['details' => function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        }])->with('customer', 'shipping')
            ->with('seller')
            ->where('id', $id)->first();

        $data['email'] = $order->customer['email'];
        $data['client_name'] = $order->customer['f_name'].' '.$order->customer['l_name'];
        $data['order'] = $order;

        $mpdf_view = \View::make('seller-views.order.invoice')->with('order', $order)->with('seller', $seller);
        Helpers::gen_mpdf($mpdf_view, 'order_invoice_', $order->id);
    }

    public function payment_status(Request $request)
    {
        if ($request->ajax()) {
            $order = Order::find($request->id);
            $order->payment_status = $request->payment_status;
            $order->save();
            $data = $request->payment_status;

            return response()->json($data);
        }
    }

    public function status(Request $request)
    {
        $order = Order::find($request->id);
        $fcm_token = $order->customer->cm_firebase_token;
        $value = Helpers::order_status_update_message($request->order_status);
        try {
            if ($value) {
                $data = [
                    'title' => translate('Order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }
        } catch (\Exception $e) {
            return response()->json([]);
        }

        if ($request->alasan) {
            $order->order_status = 'canceled';
            $order->alasan_admin = $request->alasan;
            $order->save();

            return response()->json($request->order_status);
        }

        $kamar = $request->no_kamar;
        if (strpos($kamar, 'id') !== false) {
            $rom = 'ditempat';
        } else {
            $rom = $kamar;
        }

        $status = $request->order_status;

        if ($status == 'canceled') {
            $order->order_status = $status;
            $rom = $order->roomDetail_id;
            if ($rom != null || $rom != 'ditempat') {
                $uid = '';
                OrderManager::updateRoom($rom, 1, $uid);
                $order->save();
            }
        } else {
            $order->order_status = $status;
            $order->roomDetail_id = $rom;
            $uid = $order->customer_id;
            OrderManager::updateRoom($kamar, 0, $uid);
            $order->save();
        }

        if ($order->order_status == 'delivered') {
            return response()->json(['success' => 0, 'message' => 'order is already delivered.'], 200);
        }
        $order->order_status = $request->order_status;
        OrderManager::stock_update_on_order_status_change($order, $request->order_status);

        if ($request->order_status == 'delivered' && $order['seller_id'] != null) {
            OrderManager::wallet_manage_on_order_status_change($order, 'seller');
        }

        $data = $request->order_status;

        return response()->json($data);
    }
}
