<?php

namespace App\Http\Controllers\Web;

use App\CPU\CartManager;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Booked;
use App\Model\Detail_room;
use App\Model\Order;
use App\Model\UserPoin;
use App\User;
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
        $value = $order['firstPayment'];
        $tran = OrderManager::gen_unique_id();
        $duration = '10800';
        // dd($duration);

        if ($request->type == 'direct') {
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

        Helpers::sendNotif($checkout_session, $order_id);

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

    public function next_invoice(Request $request)
    {
        // dd($request);
        $customer = auth('customer')->user();
        $order_id = $request['order_id'];
        $order = Booked::find($order_id);
        $type = strtoupper($request['type']);
        // dd($type);
        $value = $request['amount'];
        $tran = OrderManager::gen_unique_id();
        $duration = '10800';
        // dd($duration);

        if ($request->type == 'direct') {
            $order->payment_status = 'direct';
            $order->save();
            if (auth('customer')->check()) {
                Toastr::success('Silahkan lakukan pembayaran langsung.');

                return view('web-views.payment-direct');
            }
        }

        session()->put('transaction_ref', $tran);
        Xendit::setApiKey(config('xendit.apikey'));

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
            'success_redirect_url' => env('APP_URL').'/xendit-payment/nextSuccess/'.$order_id,
            'failure_redirect_url' => env('APP_URL').'/xendit-payment/nextExpired/'.$order_id,
        ];

        $checkout_session = \Xendit\Invoice::create($params);

        Helpers::sendNotif($checkout_session, $order_id);

        return redirect()->away($checkout_session['invoice_url']);
    }

    public function nextSuccess($id)
    {
        $order = Booked::with('order')->find($id);

        $seller_is = json_decode($order->order->details[0]->product_details)->added_by;

        $payment_before = Booked::where(['order_id' => $order->order_id, 'bulan_ke' => (int) $order->bulan_ke - 1])->first();

        $order->payment_status = 'paid';
        $order->current_payment = $payment_before->next_payment;
        $order->total_payyed = round((float) $payment_before->next_payment + (float) $payment_before->total_payyed);
        $order->updated_at = Carbon::now();

        $order->save();

        $saved = Booked::with('order')->where('id', $order->id)->first();
        Helpers::successPayment($saved);

        session()->forget('poin');
        $room = Detail_room::find($order->room_id);

        $user = User::where('id', $order->customer_id)->first();
        if ($user->cm_firebase_token !== null) {
            $fcm_token = $user->cm_firebase_token;

            $data = [
                    'title' => 'Paymentfor next month Successfully',
                    'description' => 'Your payment for room '.$room->name.' successfully',
                    'order_id' => $order->id,
                    'image' => '',
                ];
            Helpers::send_push_notif_to_device($fcm_token, $data);
        }

        if (auth('customer')->check()) {
            Toastr::success('Pembayaran berhasil.');

            return redirect('payment-complete');
        }

        return response()->json(['message' => 'Payment succeeded'], 200);
    }

    public function success($id)
    {
        $order = Order::with('details')->find($id);
        // dd($order);

        $seller_is = json_decode($order->details[0]->product_details)->added_by;

        if ($order->useVarian == 0) {
            $longtime = $order->durasi;

            for ($i = 0; $i < $longtime; ++$i) {
                $booked = new Booked();
                $booked->customer_id = $order->customer_id;
                $booked->seller_is = $seller_is;
                $booked->product_id = $order->details[0]->product_id;
                $booked->seller_id = $order->seller_id;
                $booked->room_id = $order->roomDetail_id;
                $booked->total_durasi = $order->durasi;
                $booked->bulan_ke = $i + 1;
                $booked->deadline = Carbon::now()->addMonth($i)->toDateTimeString();
                $booked->order_id = $order->id;
                $booked->payment_status = 'unpaid';
                $booked->order_amount = $order->order_amount;
                $booked->current_payment = 0;
                $booked->next_payment = $order->nextPayment;
                $booked->total_payyed = 0;
                $booked->next_payment_date = Carbon::now()->addMonth($i + 1)->toDateTimeString();
                $booked->save();
            }

            $bookeds = Booked::where(['customer_id' => $order->customer_id, 'room_id' => $order->roomDetail_id, 'payment_status' => 'unpaid'])->first();
            $bookeds->payment_status = 'paid';
            $bookeds->current_payment = $order->firstPayment;
            $bookeds->total_payyed += $order->firstPayment;
            $bookeds->save();

            $bookend = Booked::where(['customer_id' => $order->customer_id, 'room_id' => $order->roomDetail_id, 'payment_status' => 'unpaid'])->orderBy('id', 'desc')->first();
            if (isset($bookend)) {
                $bookend->next_payment = 0;
                $bookend->save();
            }
        } else {
            $booked = new Booked();
            $booked->customer_id = $order->customer_id;
            $booked->seller_is = $seller_is;
            $booked->product_id = $order->details[0]->product_id;
            $booked->seller_id = $order->seller_id;
            $booked->room_id = $order->roomDetail_id;
            $booked->total_durasi = $order->durasi;
            $booked->bulan_ke = 0;
            $booked->order_id = $order->id;
            $booked->payment_status = 'paid';
            $booked->order_amount = $order->order_amount;
            $booked->current_payment = $order->firstPayment;
            $booked->next_payment = 0;
            $booked->total_payyed = $order->firstPayment;
            $booked->next_payment_date = Carbon::now();
            $booked->save();
        }

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

        $saved = Booked::with('order')->where('order_id', $order->id)->first();
        Helpers::successPayment($saved);
        OrderManager::wallet_manage_on_order_status_change($order, $seller_is);

        $poin = new UserPoin();
        $poin->user_id = $order->customer_id;
        $poin->shop = $order->details[0]->price;
        $poin->persen = $order->details[0]->poinCashback;
        $poin->poin = intval($order->details[0]->price * $order->details[0]->poinCashback / 100);
        $poin->used = 0;
        $poin->save();

        session()->forget('poin');

        $user = User::where('id', $order->customer_id)->first();
        if ($user->cm_firebase_token !== null) {
            $fcm_token = $user->cm_firebase_token;

            $data = [
                    'title' => 'Payment Successfully',
                    'description' => 'Your payment for room '.$room->name.' successfully',
                    'order_id' => $order->id,
                    'image' => '',
                ];
            Helpers::send_push_notif_to_device($fcm_token, $data);
        }

        CartManager::cart_clean();
        if (auth('customer')->check()) {
            Toastr::success('Pembayaran berhasil.');

            return redirect('payment-complete');
        }

        return response()->json(['message' => 'Payment succeeded'], 200);
    }
}
