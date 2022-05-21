<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\CartManager;
use App\CPU\Helpers;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use App\Model\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function cart(Request $request)
    {
        $user = Helpers::get_customer($request);
        $cart = Cart::where(['customer_id' => $user->id])->get();
        $ktp = $request->user()->ktp;
        $cart->map(function ($data) use ($ktp) {
            $data['choices'] = json_decode($data['choices']);
            $data['variations'] = json_decode($data['variations']);
            $data['ktp'] = $ktp;

            return $data;
        });

        return response()->json($cart, 200);
    }

    public function add_to_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'start_date' => 'required',
            'usePoin' => 'required',
        ], [
            'id.required' => translate('Room ID is required!'),
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $user = $request->user()->id;

        $cart = CartManager::add_to_cart($request);

        $cart = Cart::where('customer_id', $user)->orderby('id', 'DESC')->get();
        if (count($cart) > 0) {
            $order = Order::with('details')->where('customer_id', $user)->get();
            $product_id = $cart[0]->product_id;
            foreach ($order as $val) {
                // dd($val);
                $ord = $val->details[0]->product_id;
                if ($val->order_status != 'delivered' && $val->order_status != 'canceled' && $val->order_status != 'failed' && $val->order_status != 'expired') {
                    // if ($product_id == $ord) {
                    return response()->json('Selesaikan proses booking sebelumnya dulu');
                    // }
                }
            }

            CartManager::cart_clean();
        }

        return response()->json($cart, 200);
    }

    public function update_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
            'quantity' => 'required',
        ], [
            'key.required' => translate('Cart key or ID is required!'),
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $response = CartManager::update_cart_qty($request);

        return response()->json($response);
    }

    public function remove_from_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
        ], [
            'key.required' => translate('Cart key or ID is required!'),
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $user = Helpers::get_customer($request);
        Cart::where(['id' => $request->key, 'customer_id' => $user->id])->delete();

        return response()->json(translate('successfully_removed'));
    }
}
