<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\CartManager;
use App\CPU\Convert;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\Detail_room;
use App\Model\Order;
use App\Model\UserPoin;
use Illuminate\Http\Request;
use Xendit\Xendit;

class XenditController extends Controller
{
    public function invoice(Request $request)
    {
        $customer = $request->user();
        $order_id = $request->order_id;
        $type = strtoupper($request->payment_type);

        $order = Order::find($order_id);
        $value = $order->order_amount;
        $tran = OrderManager::gen_unique_id();
        $duration = '10800';

        if ($request->payment_type == 'direct') {
            $order->order_status = 'directPay';
            $order->payment_status = 'unpaid';
            $order->transaction_ref = $tran;
            $order->save();
            $room = Detail_room::find($order->roomDetail_id);

            if (isset($room)) {
                $month = strtotime($order->mulai);
                $room->user_id = $order->customer_id;
                $room->mulai = $order->mulai;
                $room->habis = date('Y-m-d', strtotime('+'.$order->durasi.'month', $month));
                $room->save();
            }
            CartManager::cart_clean();

            return response()->json(['message' => 'Silahkan lakukan pembayaran langsung']);
        }

        Xendit::setApiKey(config('xendit.apikey'));

        $products = [];
        foreach (CartManager::get_cart() as $detail) {
            array_push($products, [
                'name' => $detail->product['name'],
            ]);
        }

        $user = [
            'given_names' => $customer->f_name,
            'email' => $customer->email,
            'mobile_number' => $customer->phone,
            'address' => $customer->district.', '.$customer->city.', '.$customer->province,
        ];

        $params = [
            'external_id' => $order_id,
            'amount' => Convert::usdToidr($value),
            'payer_email' => $customer->email,
            'description' => 'inRoom',
            'payment_methods' => [$type],
            'fixed_va' => true,
            'should_send_email' => true,
            'customer' => $user,
            'invoice_duration' => $duration,
            'success_redirect_url' => env('APP_URL').'/xendit/success/'.$order_id,
            'failure_redirect_url' => env('APP_URL').'/xendit/expired/'.$order_id,
        ];

        $checkout_session = \Xendit\Invoice::create($params);

        // return redirect()->away($checkout_session['invoice_url']);
        return response()->json(['redirect_to_this_link' => $checkout_session['invoice_url']]);
    }

    public function expire($id)
    {
        $order = Order::where(['id' => $id])->first();
        OrderManager::stock_update_on_order_status_change($order, 'canceled');
        Order::where(['id' => $id])->update([
                'order_status' => 'failed',
        ]);

        return response()->json(['message' => 'order_expired_for_order_ID'.': '.$id]);
    }

    public function success($id)
    {
        $order = Order::with('details')->find($id);
        $order->order_status = 'delivered';
        $order->payment_status = 'paid';
        $order->transaction_ref = session('transaction_ref');
        $order->save();

        $room = Detail_room::find($order->roomDetail_id);
        if (isset($room)) {
            $month = strtotime($order->mulai);
            $room->user_id = $order->customer_id;
            $room->mulai = $order->mulai;
            $room->habis = date('Y-m-d', strtotime('+'.$order->durasi.'month', $month));
            $room->save();
        }

        if ($order->usePoin == 1) {
            $poins = UserPoin::where('user_id', $order->customer_id)->where('used', 0)->get();
            foreach ($poins as $p) {
                $p->used = 1;
                $p->save();
            }
        }

        $poin = new UserPoin();
        $poin->user_id = $order->customer_id;
        $poin->shop = $order->order_amount;
        $poin->persen = $order->details[0]->poin;
        $poin->poin = intval($order->order_amount * $order->details[0]->poin / 100);
        $poin->used = 0;
        $poin->save();

        session()->forget('poin');

        CartManager::cart_clean();

        return response()->json(['message' => 'Payment succeeded'], 200);
    }
}
