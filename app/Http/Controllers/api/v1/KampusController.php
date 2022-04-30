<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Kampus;
use App\Model\Product;

class KampusController extends Controller
{
    public function getKampus()
    {
        $kampus = Product::with('kost')->get();
        $ptnIds = [];
        foreach ($kampus as $k) {
            array_push($ptnIds, $k->kost->ptn_id);
        }
        $ptn = Kampus::with('city')->whereIn('id', array_unique($ptnIds))->get();

        return response()->json($ptn);
    }

    public function get_products($ptn_id)
    {
        try {
            $products = Helpers::get_products_ptn($ptn_id);
            $format = Helpers::product_home_api_format_ptn($products);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }

        return response()->json($format, 200);
    }
}
