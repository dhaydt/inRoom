<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Product;
use Illuminate\Http\Request;

class SellerProductSaleReportController extends Controller
{
    public function index(Request $request)
    {
        $query_param = ['category_id' => $request['category_id'], 'seller_id' => $request['seller_id']];
        $cat_id = (string) $request['category_id'];
        $products = Product::where(['added_by' => 'seller'])->whereHas('order_details', function ($q) {
            $q->where('payment_status', 'paid');
        })
            ->when($request->has('seller_id') && $request['seller_id'] != 'all', function ($query) use ($request) {
                $query->where('user_id', $request['seller_id']);
            })
            ->when($request->has('category_id') && $request['category_id'] != 'all', function ($query) use ($cat_id) {
                $query->whereJsonContains('category_ids', [[['id',  $cat_id]]]);
            })->with(['order_details'])
            ->paginate(Helpers::pagination_limit())->appends($query_param);
        $category_id = $request['category_id'];
        $seller_id = $request['seller_id'];
        $categories = Category::where(['parent_id' => 0])->get();
        // dd($products);

        return view('admin-views.report.seller-product-sale', compact('products', 'categories', 'category_id', 'seller_id'));
    }

    public function sellerEarning($id)
    {
        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }

        session()->put('seller_id', $id);

        return view('admin-views.report.earning-seller');
    }

    public function set_date(Request $request)
    {
        session()->put('from_date', date('Y-m-d', strtotime($request['from'])));
        session()->put('to_date', date('Y-m-d', strtotime($request['to'])));

        return back();
    }
}
