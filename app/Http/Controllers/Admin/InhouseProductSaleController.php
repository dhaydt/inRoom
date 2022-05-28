<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Product;
use Illuminate\Http\Request;

class InhouseProductSaleController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where(['parent_id' => 0])->get();
        $query_param = ['category_id' => $request['category_id']];

        $products = Product::with('kost', 'order_details')->where(['added_by' => 'admin'])
            ->when($request->has('category_id') && $request['category_id'] != 'all', function ($query) use ($request) {
                $query->whereJsonContains('category_ids', [['id' => $request['category_id']]]);
            })
            ->whereHas('order_details', function ($q) {
                $q->where('payment_status', 'paid');
            })
            ->paginate(Helpers::pagination_limit())->appends($query_param);
        $category_id = $request['category_id'];

        // dd($products);

        return view('admin-views.report.inhouse-product-sale', compact('categories', 'category_id', 'products'));
    }
}
