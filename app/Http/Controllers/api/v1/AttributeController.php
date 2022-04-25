<?php

namespace App\Http\Controllers\api\v1;

use App\Country;
use App\Http\Controllers\Controller;
use App\Model\Attribute;
use App\Model\Product;
use Illuminate\Support\Facades\DB;
use Laravolt\Indonesia\Models\City;

class AttributeController extends Controller
{
    public function get_attributes()
    {
        $attributes = Attribute::all();

        return response()->json($attributes, 200);
    }

    public function availableCity()
    {
        // return response()->json(['city' => 'work']);
        $cities = Product::with('kost')->get()->pluck('kost.city', 'kost.city');
        $city = [];
        foreach ($cities as $c => $key) {
            $id = City::where('name', $c)->first();
            $str = ['kabupaten', 'kota'];
            $rpl = ['', ''];
            $low = strtolower($c);
            $cit = str_replace($str, $rpl, $low);

            $data = [
                 'id' => $id->id,
                 'name' => $cit,
             ];
            array_push($city, $data);
        }

        return response()->json($city);
    }

    public function city()
    {
        $city = City::get();

        return response()->json($city);
    }

    public function short_country()
    {
        $country = Country::with('product')->has('product')->get();
        $count = $country->map(function ($country) {
            return ['country' => $country->country, 'country_name' => $country->country_name];
        });

        return response()->json($count, 200);
    }

    public function country()
    {
        $country = DB::table('country')->get();
        $count = $country->map(function ($country) {
            return ['country' => $country->country, 'country_name' => $country->country_name, 'phone' => $country->phonecode];
        });
        // $country['country'] = Helpers::product_data_formatting($country['country'], true);
        $response = [
            'title' => 'location store',
            'country_list' => $count,
        ];

        return response()->json($response, 200);
    }
}
