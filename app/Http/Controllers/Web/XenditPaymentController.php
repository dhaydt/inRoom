<?php

namespace App\Http\Controllers\Web;

use App\CPU\CartManager;
use App\CPU\Convert;
use App\CPU\OrderManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Detail_room;
use App\Model\Order;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Xendit\Xendit;

class XenditPaymentController extends Controller
{
    public function index()
    {
        Xendit::setApiKey(config('xendit.apikey'));

        // $createVA = \Xendit\VirtualAccounts::create($params);
        // var_dump($createVA);
        $bank = \Xendit\VirtualAccounts::getVABanks();

        return view('admin-views.business-settings.payment-method.xendit', compact('bank'));
    }

    public function getListVa()
    {
        Xendit::setApiKey(config('xendit.apikey'));

        // $createVA = \Xendit\VirtualAccounts::create($params);
        // var_dump($createVA);
        $getVABank = \Xendit\VirtualAccounts::getVABanks();

        return response()->json([
            'data' => $getVABank,
        ])->setStatusCode('200');
    }

    public function createVa(Request $request)
    {
        // dd($request);
        Xendit::setApiKey(config('xendit.apikey'));

        $params = ['external_id' => \uniqid(),
        'bank_code' => $request->bank,
        'name' => $request->name,
        'expected_amount' => (int) $request->price,
        'is_closed' => true,
        'is_single_use' => true,
        'expiration_date' => Carbon::now()->addDay(1)->toISOString(),
    ];

        $virtual = \Xendit\VirtualAccounts::create($params);
        // dd($virtual);

        return view('web-views.finish-payment', compact('virtual'));

        // return view('admin-views.business-settings.payment-method.xendit-virtual-account', compact('virtual'));
    }

    public function invoice(Request $request)
    {
        // dd($request);

        $customer = auth('customer')->user();
        $order_id = $request['order_id'];
        $order = Order::find($order_id);
        $type = strtoupper($request['type']);
        // dd($type);
        $value = $order['order_amount'];
        $tran = OrderManager::gen_unique_id();
        $duration = '60';
        // dd($duration);

        if ($request->type == 'direct') {
            $order->order_status = 'directPay';
            $order->payment_status = 'unpaid';
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
            CartManager::cart_clean();
            if (auth('customer')->check()) {
                Toastr::success('Silahkan lakukan pembayaran langsung.');

                return view('web-views.payment-direct');
            }
        }

        session()->put('transaction_ref', $tran);
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
            'success_redirect_url' => env('APP_URL').'/xendit-payment/success/'.$order_id,
            'failure_redirect_url' => env('APP_URL').'/xendit-payment/expired/'.$order_id,
        ];

        $checkout_session = \Xendit\Invoice::create($params);

        return redirect()->away($checkout_session['invoice_url']);
    }

    public function expire($id)
    {
        $order = Order::where(['id' => $id])->first();
        OrderManager::stock_update_on_order_status_change($order, 'canceled');
        Order::where(['id' => $id])->update([
                'order_status' => 'failed',
        ]);

        Toastr::warning(translate('order_expired_for_order_ID').': '.$id);

        return redirect()->route('account-oder');
    }

    public function success($id)
    {
        // dd($type);
        // $order = Order::find($request->id);

        $order = Order::find($id);
        // $order_ids = [];
        // foreach (CartManager::get_cart_group_ids() as $group_id) {
        //     $data = [
        //         'payment_method' => 'Virtual Account'.$type,
        //         'order_status' => 'confirmed',
        //         'payment_status' => 'paid',
        //         'transaction_ref' => session('transaction_ref'),
        //         'order_group_id' => $unique_id,
        //         'cart_group_id' => $group_id,
        //     ];
        //     $order_id = OrderManager::generate_order($data);
        //     array_push($order_ids, $order_id);
        // }
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

        CartManager::cart_clean();
        if (auth('customer')->check()) {
            Toastr::success('Pembayaran berhasil.');

            return view('web-views.payment-complete');
        }

        return response()->json(['message' => 'Payment succeeded'], 200);
    }
}
