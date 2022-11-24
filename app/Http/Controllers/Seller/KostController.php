<?php

namespace App\Http\Controllers\Seller;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Kost;
use App\Model\Category;
use App\Model\DealOfTheDay;
use App\Model\Fasilitas;
use App\Model\FlashDealProduct;
use App\Model\Kampus;
use App\Model\Product;
use App\Model\Rule;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;

class KostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $products = Kost::where(['added_by' => 'seller', 'user_id' => \auth('seller')->id()])
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('name', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        } else {
            // $products = Kost::where(['added_by' => 'seller', 'user_id' => \auth('seller')->id()]);
            $products = Kost::with('rooms')->where(['seller_id' => \auth('seller')->id()]);
        }
        $products = $products->orderBy('id', 'DESC')->paginate(Helpers::pagination_limit())->appends($query_param);

        // return view('seller-views.product.list', compact('products', 'search'));

        return view('seller-views.kost.list', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rule = Rule::get();
        $fas = Fasilitas::where('tipe', 'umum')->get();
        $cat = Category::get();
        $ptn = Kampus::get();

        return view('seller-views.kost.add-new', compact('ptn', 'cat', 'fas', 'rule'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $auth = auth('seller')->id();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'penghuni' => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
        ], [
            'name.required' => 'Nama kos diperlukan!',
            'penghuni.required' => 'Jenis Penghuni diperlukan!',
            'province.required' => 'Mohon provinsi nya di isi!',
            'city.required' => 'Mohon kota nya di isi!',
            'district.required' => 'Mohon kecamatannya nya di isi!',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $img = [
            'depan' => ImageManager::upload('kost/', 'png', $request->file('depan')),
            'dalam' => ImageManager::upload('kost/', 'png', $request->file('dalam')),
            'jalan' => ImageManager::upload('kost/', 'png', $request->file('jalan')),
        ];
        $prov = Province::where('id', $request['province'])->first();
        $city = City::where('id', $request['city'])->first();

        $rules = [];
        if ($request['aturan'] !== null) {
            $rules = $request['aturan'];
        }

        $fasi = [];
        if ($request['fasilitas'] !== null) {
            $fasi = $request['fasilitas'];
        }

        $kost = new Kost();
        $kost->province = $prov->name;
        $kost->city = $city->name;
        $kost->district = $request['district'];
        $kost->note_address = $request['noteAddress'];
        $kost->seller_id = $auth;
        $kost->name = $request['name'];
        $kost->added_by = 'seller';
        $kost->category_id = $request['category'];
        $kost->penghuni = $request['penghuni'];
        $kost->deskripsi = $request['description'];
        $kost->note = $request['note'];
        $kost->ptn_id = $request['ptn'];
        $kost->aturan_id = json_encode($rules);
        $kost->address = $request['address'];
        $kost->images = json_encode($img);
        $kost->fasilitas_id = json_encode($fasi);
        if ($request->ajax()) {
            return response()->json([], 200);
        } else {
            $kost->save();

            Toastr::success('Property berhasil ditambahkan!');
            Toastr::success('Silahkan tambahkan room/kamar untuk disewakan!');

            return redirect()->route('seller.product.list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Kost::with('cities', 'dis')->find($id);
        $rules = Rule::get();
        $fas = Fasilitas::where('tipe', 'umum')->get();
        $cat = Category::get();
        $ptn = Kampus::get();

        $img = json_decode($product->images);
        $depan = $img->depan;
        $dalam = $img->dalam;
        $jalan = $img->jalan;
        $rule = json_decode($product->aturan_id);
        $fasilitas = json_decode($product->fasilitas_id);

        return view('seller-views.kost.edit', compact('ptn', 'product', 'cat', 'depan', 'dalam', 'jalan', 'rule', 'fasilitas', 'rules', 'fas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], [
            'name.required' => 'Nama properti diperlukan!',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $prov = Province::where('id', $request['province'])->first();
        $city = City::where('id', $request['city'])->first();

        $product = Kost::find($id);
        $product->name = $request->name;
        $product->province = $prov->name;
        $product->city = $city->name;
        $product->district = $request['district'];
        $product->ptn_id = $request['ptn'];
        $product->note_address = $request['noteAddress'];

        if ($product->category_id != $request->category) {
            $room = Product::where('kost_id', $id)->get();
            foreach ($room as $r) {
                $cat = [
                    'id' => $request->category,
                    'position' => 1,
                ];
                $r->category_ids = json_encode($cat);
                $r->save();
            }
        }

        $rules = [];
        if ($request->aturan !== null) {
            $rules = $request->aturan;
        }

        $fasi = [];
        if ($request->fasilitas !== null) {
            $fasi = $request->fasilitas;
        }

        $product->category_id = $request->category;

        $product->category_id = $request->category;
        $product->penghuni = $request->penghuni;
        $product->deskripsi = $request->description;
        $product->note = $request->note;
        $product->aturan_id = json_encode($rules);
        $product->fasilitas_id = json_encode($fasi);

        $product_images = json_decode($product->images);

        if ($request->ajax()) {
            return response()->json([], 200);
        } else {
            $product->images = [
                    'depan' => $request->file('depan') ? ImageManager::update('kost/', $product_images->depan, 'png', $request->file('depan')) : $product_images->depan,
                    'dalam' => $request->file('dalam') ? ImageManager::update('kost/', $product_images->dalam, 'png', $request->file('dalam')) : $product_images->dalam,
                    'jalan' => $request->file('jalan') ? ImageManager::update('kost/', $product_images->jalan, 'png', $request->file('jalan')) : $product_images->jalan,
                ];
            $product->save();
            Toastr::success('Property berhasil diupdate.');

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Kost::find($id);
        if (!isset($product)) {
            return back();
        }

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
        Toastr::success('Property berhasil dihapus!');

        return back();
    }
}
