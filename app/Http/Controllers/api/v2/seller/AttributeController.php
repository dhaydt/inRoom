<?php

namespace App\Http\Controllers\api\v2\seller;

use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Category;
use App\Model\Fasilitas;
use App\Model\Kampus;
use App\Model\Rule;

class AttributeController extends Controller
{
    public function howToUse()
    {
        $panduan = BusinessSetting::where('type', 'how_to_use')->first();

        return response()->json(['status' => 'success', 'data' => $panduan]);
    }

    public function FasilitasKost()
    {
        $data = Fasilitas::where('tipe', 'umum')->get();
        $format = [];
        foreach ($data as $d) {
            $item = [
                'id' => $d->id,
                'name' => $d->name,
            ];
            array_push($format, $item);
        }

        return response()->json($format, 200);
    }

    public function FasilitasKamar()
    {
        $data = Fasilitas::where('tipe', 'kamar')->get();
        $format = [];
        foreach ($data as $d) {
            $item = [
                'id' => $d->id,
                'name' => $d->name,
            ];
            array_push($format, $item);
        }

        return response()->json($format, 200);
    }

    public function category()
    {
        $data = Category::get();

        return response()->json($data, 200);
    }

    public function kampus()
    {
        $data = Kampus::get();

        return response()->json($data, 200);
    }

    public function rule()
    {
        $data = Rule::get();

        return response()->json($data, 200);
    }
}
