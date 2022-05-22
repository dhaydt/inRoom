<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Order;
use Illuminate\Http\Request;

class BookedController extends Controller
{
    public function list(Request $request, $status)
    {
        $query_param = [];
        $search = $request['search'];
        if (session()->has('show_inhouse_orders') && session('show_inhouse_orders') == 1) {
            $query = Order::whereHas('booked', function ($query) {
                $query->where('seller_is', 'admin');
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
                $orders = Order::with('details', 'booked', 'customer')->whereHas('booked');
            } else {
                $orders = Order::with(['customer', 'details']);
            }

            if ($request->has('search')) {
                $key = $request['search'];
                $orders = $orders->whereHas('customer', function ($q) use ($key) {
                    $q->where('f_name', 'like', "%{$key}%")
                        ->orWhere('l_name', 'like', "%{$key}%");
                })
                    ->orWhereHas('booked', function ($d) use ($key) {
                        $d->whereHas('product', function ($p) use ($key) {
                            $p->whereHas('kost', function ($k) use ($key) {
                                $k->where('name', 'like', "%{$key}%");
                            });
                        });
                    });
                // dd($orders);
                $query_param = ['search' => $request['search']];
            }
        }

        $orders = $orders->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.booked.list', compact('orders', 'search'));
    }

    public function detail($order)
    {
        $book = Order::with('booked', 'details', 'customer', 'room')->where('id', $order)->first();

        return view('admin-views.booked.order-details', compact('book'));
    }
}
