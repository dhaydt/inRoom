<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Product;

class RoomController extends Controller
{
    public function roomDetail($id)
    {
        $product = Product::with('room', 'kost')->where('id', $id)->get();
        $format = Helpers::roomFormattApi($product);

        return response()->json($format);
    }
}
