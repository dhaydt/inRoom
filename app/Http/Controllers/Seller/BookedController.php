<?php

namespace App\Http\Controllers\Seller;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Order;
use Illuminate\Http\Request;

class BookedController extends Controller
{
    public function list(Request $request, $status)
    {
        $sellerId = auth('seller')->id();
        $orders = Order::with('booked', 'customer', 'details')->where(['seller_is' => 'seller'])->where(['seller_id' => $sellerId])->whereHas('booked');

        $query_param = [];
        $search = $request['search'];
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
        $orders = $orders->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('seller-views.booked.list', compact('orders', 'search'));
    }

    public function detail($order)
    {
        $book = Order::with('booked', 'details', 'customer', 'room')->where('id', $order)->first();

        return view('seller-views.booked.order-details', compact('book'));
    }
}
