<?php

namespace App\Http\Controllers\api\v2\seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;

class AreaController extends Controller
{
    public function province()
    {
        $data = Province::get();
        $resp = [];
        foreach ($data as $d) {
            $item = [
                'id' => $d->id,
                'name' => $d->name,
            ];

            array_push($resp, $item);
        }

        return response()->json($resp, 200);
    }

    public function city(Request $request)
    {
        $city = City::where('province_id', $request->province_id)->get();

        if (count($city) == 0) {
            return response()->json(['no city not found'], 401);
        }

        $resp = [];
        foreach ($city as $d) {
            $item = [
                'id' => $d->id,
                'name' => $d->name,
            ];

            array_push($resp, $item);
        }

        return response()->json($resp, 200);
    }

    public function district(Request $request)
    {
        $data = District::where('city_id', $request->city_id);

        if (count($data->get()) == 0) {
            return response()->json(['no district not found'], 401);
        }

        return response()->json($data->pluck('name'), 200);
    }
}
