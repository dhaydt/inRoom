<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Detail_room;
use App\Model\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function roomDetail($id)
    {
        $product = Product::with('room', 'kost')->where('id', $id)->get();
        $format = Helpers::roomFormattApi($product);

        return response()->json($format);
    }

    public function addRoom(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_name' => 'required',
            'product_id' => 'required',
            ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $product = Product::find($request['product_id']);

        if (!isset($product)) {
            return response()->json(['product not fopund']);
        }

        $room = new Detail_room();
        $room->name = $request['room_name'];
        $room->room_id = $product->room_id;
        $room->save();

        $current = Detail_room::where('room_id', $product->room_id)->where('available', 1)->get();
        $available = count($current);
        $product->current_stock = $available;
        $product->save();

        return response()->json('room successfully added!');
    }

    public function room_update(Request $request)
    {
        $room = Detail_room::find($request['room_id']);

        if (!isset($room)) {
            return response()->json('room not found');
        }
        $product = Product::where('room_id', $room->room_id)->first();

        if (!isset($product)) {
            return response()->json('Product with this room id not available');
        }

        $room->available = $request['available'];
        $room->save();
        $current = Detail_room::where('room_id', $room['room_id'])->where('available', 1)->get();
        $available = count($current);

        // return $product;
        $product->current_stock = $available;

        $product->save();

        return response()->json('room status updated successfully!');
    }

    public function deleteRoom($id)
    {
        if (!isset($id)) {
            return response()->json('please input room_id');
        } else {
            $room = Detail_room::find($id);

            if (!isset($room)) {
                return response()->json('room not found!');
            }

            $room->delete();

            return response()->json('successfully deleted room!');
        }
    }
}
