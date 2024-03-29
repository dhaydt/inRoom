<?php

namespace App\CPU;

use App\Model\Admin;
use App\Model\AdminWallet;
use App\Model\Cart;
use App\Model\Detail_room;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\OrderTransaction;
use App\Model\Product;
use App\Model\Seller;
use App\Model\SellerWallet;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrderManager
{
    public static function track_order($order_id)
    {
        return Order::where(['id' => $order_id])->first();
    }

    public static function gen_unique_id()
    {
        return rand(1000, 9999).'-'.Str::random(5).'-'.time();
    }

    public static function order_summary($order)
    {
        // dd($order);
        $ord = $order->details[0];
        $sub_total = $ord->price;
        $total_tax = $ord->tax;
        if ($order->usePoin == 1) {
            $poin = $ord->poin;
        } else {
            $poin = 0;
        }
        $deposit = $ord->deposit;
        $total_discount_on_product = $ord->discount;
        // foreach ($order->details as $key => $detail) {
        //     $sub_total += $detail->price;
        //     $total_tax += $detail->tax;
        //     $total_discount_on_product += $detail->discount;
        //     if ($order->usePoin == 1) {
        //         $poin = $detail->poin;
        //     } else {
        //         $poin = 0;
        //     }
        //     $deposit += $detail->deposit;
        // }

        return [
            'subtotal' => $sub_total,
            'total_tax' => $total_tax,
            'total_discount_on_product' => $total_discount_on_product,
            'poin' => $poin,
            'deposit' => $deposit,
        ];
    }

    public static function updateRoom($id, $status, $uid)
    {
        $success = 1;
        // dd($id);
        if (strpos($id, 'id') !== false) {
            $int = str_replace('id', '', $id);
            $product = Product::where('room_id', $int)->first();
            $room = Detail_room::where('room_id', $product['room_id'])->where('available', 1)->first();
            $stock = $product['current_stock'];
            $room->user_id = 'booked';
            $room->available = $status;
            $product->save();
            $room->save();

            Helpers::room_check($room->room_id);
        } else {
            $room = Detail_room::where(['id' => $id])->first();
            $product = Product::where('room_id', $room->room_id)->first();

            $room->user_id = $uid;
            $room->available = $status;
            $product->save();
            $room->save();

            Helpers::room_check($room->room_id);
        }

        return response()->json([
            'success' => $success,
        ], 200);
    }

    public static function stock_update_on_order_status_change($order, $status)
    {
        if ($status == 'returned' || $status == 'failed' || $status == 'canceled') {
            $id = $order->roomDetail_id;
            // dd($id);
            if ($id != null) {
                if ($id == 'ditempat') {
                    $product = json_decode($order->details[0]->product_details);
                    $room_id = $product->room_id;
                    $room = Detail_room::where('room_id', $room_id)->where('user_id', 'booked')->first();
                    $room->available = 1;
                    $room->save();
                }
                if ($id != 'ditempat') {
                    $room = Detail_room::where('id', $id)->first();
                    $room->available = 1;
                    $room->save();
                }
                Helpers::room_check($room->room_id);
            }
        } else {
            foreach ($order->details as $detail) {
                if ($detail['is_stock_decreased'] == 0) {
                    $product = Product::find($detail['product_id']);

                    //check stock
                    /*foreach ($order->details as $c) {
                        $product = Product::find($c['product_id']);
                        $type = $detail['variant'];
                        foreach (json_decode($product['variation'], true) as $var) {
                            if ($type == $var['type'] && $var['qty'] < $c['qty']) {
                                Toastr::error('Stock is insufficient!');
                                return back();
                            }
                        }
                    }*/

                    $type = $detail['variant'];
                    $var_store = [];
                    // foreach (json_decode($product['variation'], true) as $var) {
                    //     if ($type == $var['type']) {
                    //         $var['qty'] -= $detail['qty'];
                    //     }
                    //     array_push($var_store, $var);
                    // }
                    OrderDetail::where(['id' => $detail['id']])->update([
                        'is_stock_decreased' => 1,
                    ]);
                }
            }
        }
    }

    public static function wallet_manage_on_order_status_change($order, $received_by)
    {
        $order = Order::with('details')->find($order['id']);
        $order_summary = OrderManager::order_summary($order);
        $order_amount = $order_summary['subtotal'] - $order_summary['total_discount_on_product'] + $order_summary['deposit'] - $order_summary['poin'] + $order_summary['total_tax'];
        $commission = Helpers::sales_commission($order);
        $shipping_model = Helpers::get_business_settings('shipping_method');

        if (AdminWallet::where('admin_id', 1)->first() == false) {
            DB::table('admin_wallets')->insert([
                'admin_id' => 1,
                'withdrawn' => 0,
                'commission_earned' => 0,
                'inhouse_earning' => 0,
                'delivery_charge_earned' => 0,
                'pending_amount' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if (SellerWallet::where('seller_id', $order['seller_id'])->first() == false) {
            DB::table('seller_wallets')->insert([
                'seller_id' => $order['seller_id'],
                'withdrawn' => 0,
                'commission_given' => 0,
                'total_earning' => 0,
                'pending_withdraw' => 0,
                'delivery_charge_earned' => 0,
                'collected_cash' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($order['payment_method'] == 'cash_on_delivery') {
            DB::table('order_transactions')->insert([
                'transaction_id' => OrderManager::gen_unique_id(),
                'customer_id' => $order['customer_id'],
                'seller_id' => $order['seller_id'],
                'seller_is' => $order['seller_is'],
                'order_id' => $order['id'],
                'order_amount' => $order_amount,
                'seller_amount' => $order_amount - $commission,
                'admin_commission' => $commission,
                'received_by' => $received_by,
                'status' => 'disburse',
                'delivery_charge' => $order['shipping_cost'],
                'tax' => $order_summary['total_tax'],
                'delivered_by' => $received_by,
                'payment_method' => $order['payment_method'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $wallet = AdminWallet::where('admin_id', 1)->first();
            $wallet->commission_earned += $commission;
            $wallet->save();

            if ($order['seller_is'] == 'admin') {
                $wallet = AdminWallet::where('admin_id', 1)->first();
                $wallet->inhouse_earning += $order_amount;
                if ($shipping_model == 'sellerwise_shipping') {
                    $wallet->delivery_charge_earned += $order['shipping_cost'];
                }
                $wallet->total_tax_collected += $order_summary['total_tax'];
                $wallet->save();
            } else {
                $wallet = SellerWallet::where('seller_id', $order['seller_id'])->first();
                $wallet->commission_given += $commission;
                $wallet->total_earning += ($order_amount - $commission);
                $wallet->total_tax_collected += $order_summary['total_tax'];

                if ($shipping_model == 'sellerwise_shipping') {
                    $wallet->delivery_charge_earned += $order['shipping_cost'];
                    $wallet->collected_cash += $order['order_amount']; //total order amount
                } else {
                    $wallet->total_earning += ($order_amount - $commission) + $order_summary['total_tax'];
                }

                $wallet->save();
            }
        } else {
            $transaction = OrderTransaction::where(['order_id' => $order['id']])->first();
            $transaction->status = 'disburse';
            $transaction->save();

            $wallet = AdminWallet::where('admin_id', 1)->first();
            $wallet->commission_earned += $commission;
            $wallet->pending_amount -= $order['order_amount'];
            if ($shipping_model == 'inhouse_shipping') {
                $wallet->delivery_charge_earned += $order['shipping_cost'];
            }
            $wallet->save();

            if ($order['seller_is'] == 'admin') {
                $wallet = AdminWallet::where('admin_id', 1)->first();
                $wallet->inhouse_earning += $order_amount;
                if ($shipping_model == 'sellerwise_shipping') {
                    $wallet->delivery_charge_earned += $order['shipping_cost'];
                }
                $wallet->total_tax_collected += $order_summary['total_tax'];
                $wallet->save();
            } else {
                $wallet = SellerWallet::where('seller_id', $order['seller_id'])->first();
                $wallet->commission_given += $commission;

                if ($shipping_model == 'sellerwise_shipping') {
                    $wallet->delivery_charge_earned += $order['shipping_cost'];
                    $wallet->total_earning += ($order_amount - $commission) + $order_summary['total_tax'] + $order['shipping_cost'];
                } else {
                    $wallet->total_earning += ($order_amount - $commission) + $order_summary['total_tax'];
                }

                $wallet->total_tax_collected += $order_summary['total_tax'];
                $wallet->save();
            }
        }
    }

    public static function generate_order($data)
    {
        $date = mb_strimwidth(Carbon::now()->getTimestamp(), 7, 3);
        $order_id = (100000 + Order::all()->count() + 1).$date;

        if (Order::find($order_id)) {
            $order_id = Order::orderBy('id', 'DESC')->first()->id + 1;
        }
        $address_id = session('address_id') ? session('address_id') : null;
        $coupon_code = session()->has('coupon_code') ? session('coupon_code') : 0;
        $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;

        $req = array_key_exists('request', $data) ? $data['request'] : null;
        if ($req != null) {
            if (session()->has('coupon_code') == false) {
                $coupon_code = $req->has('coupon_code') ? $req['coupon_code'] : null;
                $discount = $req->has('coupon_code') ? Helpers::coupon_discount($req) : $discount;
            }
            if (session()->has('address_id') == false) {
                $address_id = $req->has('address_id') ? $req['address_id'] : null;
            }
        }
        $user = Helpers::get_customer($req);

        if ($discount > 0) {
            $discount = round($discount / count(CartManager::get_cart_group_ids($req)));
        }
        if (!isset($data['api'])) {
            $id = auth('customer')->id();
            $amount = $data['data']->anchor;
            $tambahan = $data['data']->catatan_tambahan;
            $penyewa = $data['data']->penyewa;
        } else {
            $id = $data['data']->customer_id;
            $amount = (int) $data['request']->qty;
            $tambahan = $data['request']->catatan_tambahan;
            $penyewa = $data['request']->penyewa;
        }

        // dd($data);
        $user = User::find($id);

        $cart_group_id = $data['cart_group_id'];
        $seller_data = Cart::where(['cart_group_id' => $cart_group_id])->first();
        $minute = 2880;

        $product = Product::find($seller_data['product_id']);

        $deposit = $seller_data['deposit'] ? $seller_data['deposit'] : 0;
        $used = 0;
        if ($seller_data['usePoin'] == 1) {
            $used = $seller_data['poin'];
        }
        // dd($data['data']);
        if (isset($data['api'])) {
            if ($data['api']->varian == 'true') {
                $order_price = ((CartManager::cart_grand_total($cart_group_id) - $discount) * $amount) + $deposit - $used;
                $firstPayment = $order_price;

                $useVarian = 1;
                $next = 0;
            } else {
                if ($amount > 1) {
                    $order_price = ((CartManager::cart_grand_total($cart_group_id)) * $amount) - $discount + $deposit - $used;
                    $firstPayment = ((CartManager::cart_grand_total($cart_group_id) - $discount) + $deposit - $used);
                    $next = ($order_price - $firstPayment) / ($amount - 1);
                    $data['data']->durasi = $amount;
                    $useVarian = 0;
                } else {
                    $order_price = ((CartManager::cart_grand_total($cart_group_id)) * $amount) - $discount + $deposit - $used;
                    $firstPayment = $order_price;
                    $next = 0;
                    $useVarian = 0;
                }
            }
        } else {
            if ($data['data']->varian == 'true') {
                $order_price = ((CartManager::cart_grand_total($cart_group_id)) * $amount) - $discount + $deposit - $used;
                $firstPayment = $order_price;

                $useVarian = 1;
                $next = 0;
            } else {
                if ($amount > 1) {
                    $order_price = (CartManager::cart_grand_total($cart_group_id) * $amount) - $discount + $deposit - $used;
                    $firstPayment = ((CartManager::cart_grand_total($cart_group_id)) - $discount + $deposit - $used);
                    $next = ($order_price - $firstPayment) / ($amount - 1);
                    $useVarian = 0;
                } else {
                    $order_price = (CartManager::cart_grand_total($cart_group_id) * $amount) - $discount + $deposit - $used;
                    $firstPayment = $order_price;
                    $next = 0;
                    $useVarian = 0;
                }
            }
        }

        $or = [
            'id' => $order_id,
            'verification_code' => rand(100000, 999999),
            'customer_id' => $user->id,
            'seller_id' => $seller_data->seller_id,
            'seller_is' => $seller_data->seller_is,
            'mulai' => $seller_data->mulai,
            'durasi' => $data['data']->durasi,
            'ktp' => $user->ktp,
            'jumlah_penyewa' => $penyewa,
            'catatan_tambahan' => $tambahan,
            'customer_type' => 'customer',
            'payment_status' => $data['payment_status'],
            'order_status' => $data['order_status'],
            'auto_cancel' => Carbon::now()->addMinute($minute),
            'payment_method' => $data['payment_method'],
            'transaction_ref' => $data['transaction_ref'],
            'order_group_id' => $data['order_group_id'],
            'discount_amount' => $discount,
            'discount_type' => $discount == 0 ? null : 'coupon_discount',
            'coupon_code' => $coupon_code,
            'usePoin' => $seller_data['usePoin'],
            'order_amount' => $order_price,
            'useVarian' => $useVarian,
            'nextPayment' => $next,
            'firstPayment' => $firstPayment,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $order_id = DB::table('orders')->insertGetId($or);

        foreach (CartManager::get_cart($data['cart_group_id']) as $c) {
            $product = Product::where(['id' => $c['product_id']])->first();
            $penyewa = Auth('customer')->user();
            if (!isset($penyewa)) {
                $penyewa = $user;
            }
            $or_d = [
                'order_id' => $order_id,
                'product_id' => $c['product_id'],
                'seller_id' => $c['seller_id'],
                'product_details' => $product,
                'data_penyewa' => $penyewa,
                'qty' => $data['data']->durasi,
                'price' => $c['price'],
                'tax' => $c['tax'] * $c['quantity'],
                'discount' => $c['discount'] * $c['quantity'],
                'discount_type' => 'discount_on_product',
                'variant' => $c['variant'],
                'variation' => $c['variations'],
                'delivery_status' => 'pending',
                'deposit' => $seller_data['deposit'],
                'poin' => $seller_data['poin'],
                'poinCashback' => $seller_data['poinCashback'],
                'payment_status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($c['variant'] != null) {
                $type = $c['variant'];
                $var_store = [];
                foreach (json_decode($product['variation'], true) as $var) {
                    if ($type == $var['type']) {
                        $var['qty'] -= $c['quantity'];
                    }
                    array_push($var_store, $var);
                }
                Product::where(['id' => $product['id']])->update([
                    'variation' => json_encode($var_store),
                ]);
            }

            Product::where(['id' => $product['id']])->update([
                // 'current_stock' => $product['current_stock'] - $c['quantity'],
            ]);

            DB::table('order_details')->insert($or_d);
        }

        if ($or['payment_method'] != 'cash_on_delivery') {
            $order = Order::find($order_id);
            $order_summary = OrderManager::order_summary($order);
            $order_amount = $order_summary['subtotal'] - $order_summary['total_discount_on_product'] - $order['discount'];
            $commission = Helpers::sales_commission($order);

            DB::table('order_transactions')->insert([
                'transaction_id' => OrderManager::gen_unique_id(),
                'customer_id' => $order['customer_id'],
                'seller_id' => $order['seller_id'],
                'seller_is' => $order['seller_is'],
                'order_id' => $order_id,
                'order_amount' => $order_amount,
                'seller_amount' => $order_amount - $commission,
                'admin_commission' => $commission,
                'received_by' => 'admin',
                'status' => 'hold',
                'delivery_charge' => $order['shipping_cost'],
                'tax' => $order_summary['total_tax'],
                'delivered_by' => 'admin',
                'payment_method' => $or['payment_method'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (AdminWallet::where('admin_id', 1)->first() == false) {
                DB::table('admin_wallets')->insert([
                    'admin_id' => 1,
                    'withdrawn' => 0,
                    'commission_earned' => 0,
                    'inhouse_earning' => 0,
                    'delivery_charge_earned' => 0,
                    'pending_amount' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            DB::table('admin_wallets')->where('admin_id', $order['seller_id'])->increment('pending_amount', $order['order_amount']);
        }

        try {
            $fcm_token = $user->cm_firebase_token;
            if ($data['payment_method'] != 'cash_on_delivery') {
                $value = Helpers::order_status_update_message('confirmed');
            } else {
                $value = Helpers::order_status_update_message('pending');
            }

            if ($value) {
                $data = [
                    'title' => translate('order'),
                    'description' => $value,
                    'order_id' => $order_id,
                    'image' => '',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
                $orders = Order::find($order_id);
                if ($orders['seller_is'] == 'seller') {
                    $value = Helpers::order_status_incoming_message('pending');
                    $data = [
                        'title' => 'Order Baru',
                        'description' => $value,
                        'order_id' => $order_id,
                        'image' => '',
                    ];
                    $seller_id = $seller_data->seller_id;
                    $seller_fcm = Seller::find($seller_id);
                    Helpers::send_push_notif_to_device($seller_fcm->cm_firebase_token, $data);
                }
            }

            Mail::to($user->email)->send(new \App\Mail\OrderPlaced($order_id));
            if ($order['seller_is'] == 'seller') {
                $seller = Seller::where(['id' => $seller_data->seller_id])->first();
            } else {
                $seller = Admin::where(['admin_role_id' => 1])->first();
            }
            Mail::to($seller->email)->send(new \App\Mail\OrderReceivedNotifySeller($order_id));
        } catch (\Exception $exception) {
        }

        return $order_id;
    }
}
