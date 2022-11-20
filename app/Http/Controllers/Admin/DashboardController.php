<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\AdminWallet;
use App\Model\Brand;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\OrderTransaction;
use App\Model\Product;
use App\Model\SellerWalletHistory;
use App\Model\Shop;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class DashboardController extends Controller
{
    public function refreshOrderStatus()
    {
        $orders = Order::with('details')->get();
        foreach ($orders as $order) {
            if ($order['order_status'] == 'processing') {
                $details = OrderDetail::where('order_id', $order['id'])->get();
                foreach ($details as $d) {
                    $d->delivery_status = 'processing';
                    $d->save();
                }
            }
            if ($order['order_status'] == 'canceled') {
                $details = OrderDetail::where('order_id', $order['id'])->get();
                foreach ($details as $d) {
                    $d->delivery_status = 'canceled';
                    $d->save();
                }
            }
            if ($order['order_status'] == 'delivered') {
                $details = OrderDetail::where('order_id', $order['id'])->get();
                foreach ($details as $d) {
                    $d->delivery_status = 'delivered';
                    $d->payment_status = 'paid';
                    $d->save();
                }
            }
            if ($order['order_status'] == 'expired') {
                $details = OrderDetail::where('order_id', $order['id'])->get();
                foreach ($details as $d) {
                    $d->delivery_status = 'expired';
                    $d->save();
                }
            }
        }

        Toastr::success('Order Status updated successfully');

        return redirect()->route('admin.report.seller-product-sale');
    }

    public function getApplied()
    {
        $apply = Helpers::sellerApply();

        return $apply;
    }

    public function dashboard()
    {
        // $top_sell = OrderDetail::with(['product'])
        //     ->select('product_id', DB::raw('SUM(qty) as count'))
        //     ->groupBy('product_id')
        //     ->orderBy("count", 'desc')
        //     ->take(6)
        //     ->get();
        $top = Product::with('order_details')->get();
        $sell = [];
        foreach ($top as $t) {
            $count = count($t->order_details);
            $desc = $t;
            $item = [
                'count' => $count,
                'product' => $desc,
            ];
            array_push($sell, $item);
        }
        $top_sell = collect($sell)->sortBy('count')->reverse()->take(6)->toArray();

        $most_rated_products = Product::rightJoin('reviews', 'reviews.product_id', '=', 'products.id')
            ->groupBy('product_id')
            ->select(['product_id',
                DB::raw('AVG(reviews.rating) as ratings_average'),
                DB::raw('count(*) as total'),
            ])
            ->orderBy('total', 'desc')
            ->take(6)
            ->get();

        $top_store_by_earning = SellerWalletHistory::select('seller_id', DB::raw('SUM(amount) as count'))
            ->groupBy('seller_id')
            ->orderBy('count', 'desc')
            ->take(6)
            ->get();

        $top_customer = Order::with(['customer'])
            ->select('customer_id', DB::raw('COUNT(customer_id) as count'))
            ->groupBy('customer_id')
            ->orderBy('count', 'desc')
            ->take(6)
            ->get();

        $top_store_by_order_received = Order::where('seller_is', 'seller')
            ->select('seller_id', DB::raw('COUNT(id) as count'))
            ->groupBy('seller_id')
            ->orderBy('count', 'desc')
            ->take(6)
            ->get();

        $from = Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');

        $inhouse_data = [];
        $inhouse_earning = Order::where([
            'seller_is' => 'admin',
            'order_status' => 'delivered',
        ])->select(
            DB::raw('IFNULL(sum(order_amount),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
        for ($inc = 1; $inc <= 12; ++$inc) {
            $inhouse_data[$inc] = 0;
            foreach ($inhouse_earning as $match) {
                if ($match['month'] == $inc) {
                    $inhouse_data[$inc] = $match['sums'];
                }
            }
        }

        $seller_data = [];
        $seller_earnings = Order::where([
            'seller_is' => 'seller',
            'order_status' => 'delivered',
        ])->select(
            DB::raw('IFNULL(sum(order_amount),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
        for ($inc = 1; $inc <= 12; ++$inc) {
            $seller_data[$inc] = 0;
            foreach ($seller_earnings as $match) {
                if ($match['month'] == $inc) {
                    $seller_data[$inc] = $match['sums'];
                }
            }
        }

        $commission_data = [];
        $commission_earnings = OrderTransaction::where([
            'status' => 'disburse',
        ])->select(
            DB::raw('IFNULL(sum(admin_commission),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
        for ($inc = 1; $inc <= 12; ++$inc) {
            $commission_data[$inc] = 0;
            foreach ($commission_earnings as $match) {
                if ($match['month'] == $inc) {
                    $commission_data[$inc] = $match['sums'];
                }
            }
        }

        $data = self::order_stats_data();
        $data['customer'] = User::count();
        $data['store'] = Shop::count();
        $data['product'] = Product::count();
        $data['order'] = Order::count();
        $data['brand'] = Brand::count();

        $data['top_sell'] = $top_sell;
        $data['most_rated_products'] = $most_rated_products;
        $data['top_store_by_earning'] = $top_store_by_earning;
        $data['top_customer'] = $top_customer;
        $data['top_store_by_order_received'] = $top_store_by_order_received;

        $admin_wallet = AdminWallet::where('admin_id', 1)->first();
        $data['inhouse_earning'] = $admin_wallet->inhouse_earning;
        $data['commission_earned'] = $admin_wallet->commission_earned;
        $data['delivery_charge_earned'] = $admin_wallet->delivery_charge_earned;
        $data['pending_amount'] = $admin_wallet->pending_amount;
        $data['total_tax_collected'] = $admin_wallet->total_tax_collected;

        return view('admin-views.system.dashboard', compact('data', 'inhouse_data', 'seller_data', 'commission_data'));
    }

    public function order_stats(Request $request)
    {
        session()->put('statistics_type', $request['statistics_type']);
        $data = self::order_stats_data();

        return response()->json([
            'view' => view('admin-views.partials._dashboard-order-stats', compact('data'))->render(),
        ], 200);
    }

    public function order_stats_data()
    {
        $today = session()->has('statistics_type') && session('statistics_type') == 'today' ? 1 : 0;
        $this_month = session()->has('statistics_type') && session('statistics_type') == 'this_month' ? 1 : 0;

        $pending = Order::where(['order_status' => 'pending'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $confirmed = Order::where(['order_status' => 'confirmed'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $processing = Order::where(['order_status' => 'processing'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $out_for_delivery = Order::where(['order_status' => 'out_for_delivery'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $delivered = Order::where(['order_status' => 'delivered'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $canceled = Order::where(['order_status' => 'canceled'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $returned = Order::where(['order_status' => 'returned'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $expired = Order::where(['order_status' => 'expired'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $failed = Order::where(['order_status' => 'failed'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();

        $data = [
            'pending' => $pending,
            'confirmed' => $confirmed,
            'processing' => $processing,
            'out_for_delivery' => $out_for_delivery,
            'delivered' => $delivered,
            'canceled' => $canceled,
            'returned' => $returned,
            'expired' => $expired,
            'failed' => $failed,
        ];

        return $data;
    }
}
