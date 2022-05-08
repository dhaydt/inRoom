<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Kost;
use App\Model\DealOfTheDay;
use App\Model\FlashDealProduct;
use App\Model\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;

class KostController extends Controller
{
    public function list(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        return response()->json(Kost::where(['added_by' => 'seller', 'seller_id' => $seller['id']])->get(), 200);
    }

    public function create(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $auth = $seller->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'penghuni' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
            'district_name' => 'required',
        ], [
            'name.required' => 'Nama kos diperlukan!',
            'penghuni.required' => 'Jenis Penghuni diperlukan!',
            'province_id.required' => 'Mohon provinsi nya di isi!',
            'city_id.required' => 'Mohon kota nya di isi!',
            'district_name.required' => 'Mohon kecamatannya nya di isi!',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $img = [
            'depan' => ImageManager::upload('kost/', 'png', $request->file('depan')),
            'dalam' => ImageManager::upload('kost/', 'png', $request->file('dalam')),
            'jalan' => ImageManager::upload('kost/', 'png', $request->file('jalan')),
        ];
        $prov = Province::where('id', $request['province_id'])->first();
        $city = City::where('id', $request['city_id'])->first();

        $kost = new Kost();
        $kost->province = $prov->name;
        $kost->city = $city->name;
        $kost->district = $request['district_name'];
        $kost->note_address = $request['catatan_alamat'];
        $kost->seller_id = $auth;
        $kost->added_by = 'seller';
        $kost->category_id = $request['category'];
        $kost->ptn_id = $request['ptn_id'];
        $kost->name = $request['name'];
        $kost->penghuni = $request['penghuni'];
        $kost->deskripsi = $request['description'];
        $kost->note = $request['note'];
        $kost->aturan_id = json_encode($request['aturan_id']);
        $kost->address = $request['address'];
        $kost->images = json_encode($img);
        $kost->fasilitas_id = json_encode($request['fasilitas_id']);

        $kost->save();

        return response()->json('property successfully added', 200);
    }

    public function update(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], [
            'name.required' => 'Nama properti diperlukan!',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $prov = Province::where('id', $request['province_id'])->first();
        $city = City::where('id', $request['city_id'])->first();

        $product = Kost::find($request->property_id);
        if (!isset($product)) {
            return response()->json('Property not found', 401);
        }
        $product->name = $request->name;
        $product->province = $prov->name;
        $product->city = $city->name;
        $product->district = $request['district_name'];
        $product->ptn_id = $request['ptn_id'];
        $product->note_address = $request['catatan_alamat'];

        if ($product->category_id != $request->category_id) {
            $room = Product::where('kost_id', $request->property_id)->get();
            foreach ($room as $r) {
                $cat = [
                    'id' => $request->category_id,
                    'position' => 1,
                ];
                $r->category_ids = json_encode($cat);
                $r->save();
            }
        }

        $product->category_id = $request->category_id;
        $product->penghuni = $request->penghuni;
        $product->deskripsi = $request->description;
        $product->note = $request->note;
        $product->aturan_id = json_encode($request->aturan_id);
        $product->fasilitas_id = json_encode($request->fasilitas_id);

        $product_images = json_decode($product->images);

        $product->images = json_encode([
                'depan' => $request->file('depan') ? ImageManager::update('kost/', $product_images->depan, 'png', $request->file('depan')) : $product_images->depan,
                'dalam' => $request->file('dalam') ? ImageManager::update('kost/', $product_images->dalam, 'png', $request->file('dalam')) : $product_images->dalam,
                'jalan' => $request->file('jalan') ? ImageManager::update('kost/', $product_images->jalan, 'png', $request->file('jalan')) : $product_images->jalan,
            ]);
        $product->save();

        return response()->json('successfully update property', 200);
    }

    public function destroy(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $product = Kost::find($request->kost_id);
        if (!isset($product)) {
            return back();
        }

        $id = $request->kost_id;

        $images = json_decode($product['images']);

        if (isset($images->depan)) {
            ImageManager::delete('/kost/'.$images->depan);
        }
        if (isset($images->dalam)) {
            ImageManager::delete('/kost/'.$images->dalam);
        }
        if (isset($images->jalan)) {
            ImageManager::delete('/kost/'.$images->jalan);
        }

        $product->delete();
        FlashDealProduct::where(['product_id' => $id])->delete();
        DealOfTheDay::where(['product_id' => $id])->delete();
        Product::where('kost_id', $id)->delete();

        return response()->json('Kost successfully deleted', 200);
    }
}
