<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\Helpers;
use App\CPU\OrderManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Seller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function generate_invoice(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
            $sellerId = Seller::findOrFail($seller->id)->gst;

            $order = Order::with(['details' => function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            }])->with('customer', 'shipping')
            ->with('seller')
            ->where('id', $id)->first();

            $data['email'] = $order->customer['email'];
            $data['client_name'] = $order->customer['f_name'].' '.$order->customer['l_name'];
            $data['order'] = $order;

            $mpdf_view = \View::make('seller-views.order.invoice')->with('order', $order)->with('seller', $sellerId);
            Helpers::gen_mpdf($mpdf_view, 'order_invoice_'.Carbon::now(), $order->id);
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }
    }

    public function list(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $order_ids = OrderDetail::where(['seller_id' => $seller['id']])->pluck('order_id')->toArray();

        return response()->json(Order::with(['customer'])->whereIn('id', $order_ids)->orderBy('created_at', 'desc')->get(), 200);
    }

    public function details(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $details = OrderDetail::with('order')->where(['seller_id' => $seller['id'], 'order_id' => $id])->get();
        foreach ($details as $det) {
            $det['product_details'] = Helpers::product_data_formatting(json_decode($det['product_details'], true));
        }

        return response()->json($details, 200);
    }

    public function order_detail_status(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $order = Order::find($request->id);

        try {
            $fcm_token = $order->customer->cm_firebase_token;
            $value = Helpers::order_status_update_message($request->order_status);
            if ($value) {
                $notif = [
                    'title' => translate('Order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $notif);
            }
        } catch (\Exception $e) {
        }

        if ($request->alasan_penolakan != null) {
            $order->order_status = 'canceled';
            $order->alasan_admin = $request->alasan_penolakan;
            $order->save();

            return response()->json($request->order_status);
        }

        $kamar = $request->room_id;
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
            return response()->json(['success' => 0, 'message' => translate('order is already delivered')], 200);
        }
        $order->order_status = $request->order_status;
        OrderManager::stock_update_on_order_status_change($order, $request->order_status);

        if ($request->order_status == 'delivered' && $order['seller_id'] != null) {
            OrderManager::wallet_manage_on_order_status_change($order, 'seller');
        }

        $order->save();

        return response()->json(['success' => 1, 'message' => translate('order_status_updated_successfully')], 200);
    }
}
