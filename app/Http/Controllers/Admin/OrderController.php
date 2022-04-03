<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\OrderManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\OrderTransaction;
use App\Model\Seller;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkStatus()
    {
        $order = Order::where('order_status', 'pending')->get();
        $now = Carbon::now()->toDateTimeString();
        $status = [];
        foreach ($order as $o) {
            $ex = $o->auto_cancel;
            if ($ex < $now) {
                $o->order_status = 'expired';
                $o->save();
            }
        }

        return $status;
    }

    public function list(Request $request, $status)
    {
        $query_param = [];
        $search = $request['search'];
        if (session()->has('show_inhouse_orders') && session('show_inhouse_orders') == 1) {
            $query = Order::whereHas('details', function ($query) {
                $query->whereHas('product', function ($query) {
                    $query->where('added_by', 'admin');
                });
            })->with(['customer', 'details']);

            if ($status != 'all') {
                $orders = $query->where(['order_status' => $status]);
            } else {
                $orders = $query;
            }

            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $orders = $orders->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_ref', 'like', "%{$value}%");
                    }
                });
                $query_param = ['search' => $request['search']];
            }
        } else {
            if ($status != 'all') {
                $orders = Order::with(['customer', 'details'])->where(['order_status' => $status]);
            } else {
                $orders = Order::with(['customer', 'details']);
            }

            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $orders = $orders->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_ref', 'like', "%{$value}%");
                    }
                });
                $query_param = ['search' => $request['search']];
            }
        }

        $orders = $orders->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.order.list', compact('orders', 'search'));
    }

    public function details($id)
    {
        $order = Order::with('details', 'shipping', 'seller', 'room', 'customer')->where(['id' => $id])->first();
        $linked_orders = Order::where(['order_group_id' => $order['order_group_id']])
            ->whereNotIn('order_group_id', ['def-order-group'])
            ->whereNotIn('id', [$order['id']])
            ->get();

        return view('admin-views.order.order-details', compact('order', 'linked_orders'));
    }

    public function manual_upload(Request $request)
    {
        if ($request->no_kamar == null) {
            Toastr::warning('Mohon pilih no kamar yang akan ditempati!');

            return redirect()->back();
        }
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

            $kamar = $request->no_kamar;
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
            $uid = $order->customer_id;
            // dd($rom);
            OrderManager::updateRoom($kamar, 0, $uid);
            $order->save();

            Toastr::success('Bukti transfer berhasil di upload');

            return redirect()->back();
        }
        Toastr::warning('Mohon pilih bukti transfer!!!!');

        return redirect()->back();
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

        if ($status == 'canceled' || $status == 'expired') {
            $order->order_status = $status;
            $rom = $order->roomDetail_id;
            if ($rom != null || $rom != 'ditempat') {
                $uid = '';
                OrderManager::updateRoom($rom, 1, $uid);
                $order->save();
            }
        } else {
            // dd($order);
            $order->order_status = $status;
            $order->roomDetail_id = $rom;
            $uid = $order->customer_id;
            // dd($rom);
            OrderManager::updateRoom($kamar, 0, $uid);
            $order->save();
        }

        $transaction = OrderTransaction::where(['order_id' => $order['id']])->first();
        if (isset($transaction) && $transaction['status'] == 'disburse') {
            return response()->json($request->order_status);
        }

        if ($request->order_status == 'delivered' && $order['seller_id'] != null) {
            OrderManager::wallet_manage_on_order_status_change($order, 'admin');
        }

        return response()->json($request->order_status);
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

    public function generate_invoice($id)
    {
        $order = Order::with('seller')->with('shipping')->with('details')->where('id', $id)->first();
        $seller = Seller::findOrFail($order->details->first()->seller_id);
        $data['email'] = $order->customer['email'];
        $data['client_name'] = $order->customer['f_name'].' '.$order->customer['l_name'];
        $data['order'] = $order;

        $mpdf_view = \View::make('admin-views.order.invoice')->with('order', $order)->with('seller', $seller);
        Helpers::gen_mpdf($mpdf_view, 'order_invoice_', $order->id);
    }

    public function inhouse_order_filter()
    {
        if (session()->has('show_inhouse_orders') && session('show_inhouse_orders') == 1) {
            session()->put('show_inhouse_orders', 0);
        } else {
            session()->put('show_inhouse_orders', 1);
        }

        return back();
    }
}
