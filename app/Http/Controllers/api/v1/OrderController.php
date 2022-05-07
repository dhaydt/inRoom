<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\OrderManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use App\Model\Order;
use App\Model\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function track_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        return response()->json(OrderManager::track_order($request['order_id']), 200);
    }

    public function place_order(Request $request)
    {
        $id = $request->user()->id;
        $user = User::find($id);
        $image = $request->file('ktp');

        if ($image != null) {
            $imageName = ImageManager::update('ktp/', $user->ktp, 'png', $request->file('ktp'));
            $user->ktp = $imageName;
            $user->save();
        }

        $user = User::find($id);
        if ($user->ktp == null) {
            return response()->json(['error' => 'tolong upload ktp anda'], 500);
        }
        $data = Cart::find($request->cart_id);
        // dd($data);
        $check = Product::find($data->product_id);
        if ($check->current_stock < 1) {
            CartManager::cart_clean($request);

            return response()->json('Maaf, kamar sudah penuh', 200);
        }
        $unique_id = $request->user()->id.'-'.rand(000001, 999999).'-'.time();
        $order_ids = [];
        foreach (CartManager::get_cart_group_ids($request) as $group_id) {
            $data = [
                'payment_method' => 'cash_on_delivery',
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'transaction_ref' => '',
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id,
                'request' => $request,
                'api' => $request,
                'data' => $data,
            ];
            $order_id = OrderManager::generate_order($data);
            array_push($order_ids, $order_id);
        }

        CartManager::cart_clean($request);

        return response()->json(translate('order_placed_successfully'), 200);
    }

    public function cancel(Request $request)
    {
        $id = $request['order_id'];
        $order = Order::where(['id' => $id])->first();
        if ($order['payment_method'] == 'cash_on_delivery' && $order['order_status'] == 'pending' || $order['order_status'] == 'processing') {
            $alasan = $request['alasan'];
            if (isset($alasan)) {
                $alasan = $request['alasan'];
            } else {
                $alasan = 'NULL';
            }
            OrderManager::stock_update_on_order_status_change($order, 'canceled');
            Order::where(['id' => $id])->update([
                'order_status' => 'canceled',
                'alasan_user' => $alasan,
            ]);

            return response()->json(['Booking_successfully_cancelled'], 200);
        }

        return response()->json(['Cancel_Booking_failed'], 200);
    }
}
