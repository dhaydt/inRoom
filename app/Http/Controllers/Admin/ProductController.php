<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\BaseController;
use App\Kost;
use App\Model\Brand;
use App\Model\Category;
use App\Model\Color;
use App\Model\DealOfTheDay;
use App\Model\Detail_room;
use App\Model\Fasilitas;
use App\Model\FlashDealProduct;
use App\Model\Product;
use App\Model\Review;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductController extends BaseController
{
    public function add_new()
    {
        $kost = Kost::where('added_by', 'admin')->get();
        $fas = Fasilitas::where('tipe', 'kamar')->get();

        return view('admin-views.product.add-new', compact('kost', 'fas'));
    }

    public function add_room(Request $request)
    {
        // dd($request);
        $check = Detail_room::where('room_id', $request->room_id)->where('name', $request->name)->first();
        $status = $request->status;

        if ($status == 1) {
            $avai = 0;
        } else {
            $avai = 1;
        }

        if ($check) {
            Toastr::warning('Nama kamar tidak boleh sama dengan kamar lain!');

            return back();
        }

        $room = new Detail_room();
        $room->room_id = $request->room_id;
        $room->name = $request->name;
        $room->available = $avai;
        $room->save();

        $update = Helpers::room_check($request->room_id);
        Toastr::success('Kamar berhasil ditambahkan! Total kamar ada '.$update);

        return redirect()->back();
    }

    public function del_room($id)
    {
        $room = Detail_room::where('id', $id)->first();
        $room->delete();
        $avai = Helpers::room_check($room->room_id);
        Toastr::success('Kamar berhasil dihapus!! Sisa kamar = '.$avai);

        return redirect()->back();
    }

    public function featured_status(Request $request)
    {
        $product = Product::find($request->id);
        $product->featured = ($product['featured'] == 0 || $product['featured'] == null) ? 1 : 0;
        $product->save();
        $data = $request->status;

        return response()->json($data);
    }

    public function approve_status(Request $request)
    {
        $product = Product::find($request->id);
        $product->request_status = 1;
        $product->save();

        return redirect()->route('admin.product.list', ['seller', 'status' => $product['request_status']]);
    }

    public function deny(Request $request)
    {
        $product = Product::find($request->id);
        $product->request_status = 2;
        $product->denied_note = $request->denied_note;
        $product->save();

        return redirect()->route('admin.product.list', ['seller', 'status' => 2]);
    }

    public function view($id)
    {
        $product = Product::with(['reviews'])->where(['id' => $id])->first();
        $reviews = Review::where(['product_id' => $id])->paginate(Helpers::pagination_limit());
        $rooms = Detail_room::where('room_id', $product->room_id)->get();

        return view('admin-views.product.view', compact('product', 'reviews', 'rooms'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'kost_id' => 'required',
            'type' => 'required',
            'images' => 'required',
            'size' => 'required',
            'tax' => 'required|min:0',
            'rooms' => 'required',
            'total' => 'required',
            'unit_price' => 'required|numeric|min:1',
        ], [
            'kost_id.required' => 'Pilih property yang ingin ditambah!',
            'images.required' => 'Foto kamar diperlukan!',
            'size.required' => 'Ukuran kamar dipeprlukan!',
            'rooms.required' => 'Mohon isi ketersediaan kamar!',
        ]);

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['unit_price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['unit_price'] <= $dis) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'unit_price', 'Discount can not be more or equal to the price!'
                );
            });
        }

        $room = json_decode($request['rooms'][0]);
        if ($room == null) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'rooms', 'Mohon isi ketersediaan kamar!'
                );
            });
        }

        if ($request['total'] == 0) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'total', 'Jumlah total kamar tidak boleh 0!'
                );
            });
        }

        $rooms_id = 100000 + Product::all()->count() + 1;

        $product = new Product();
        $product->user_id = auth('admin')->id();
        $product->added_by = 'admin';
        $product->room_id = $rooms_id;
        $product->fasilitas_id = json_encode($request->fasilitas);
        $product->type = $request->type;
        $product->kost_id = $request->kost_id;
        $product->slug = Str::slug($request->type, '-').'-'.Str::random(6);

        $kost = Kost::where('id', $request->kost_id)->first();

        $category = [
            'id' => $kost->category_id,
            'position' => 1, ];

        $product->category_ids = json_encode($category);
        // $product->brand_id = $request->brand_id;
        // $product->unit = $request->unit;
        // $product->details = $request->description[array_search('en', $request->lang)];

        if ($request->file('images')) {
            foreach ($request->file('images') as $img) {
                $product_images[] = ImageManager::upload('product/', 'png', $img);
            }
            $product->images = json_encode($product_images);
        }
        // $product->thumbnail = ImageManager::upload('product/thumbnail/', 'png', $request->file('image'));

        // if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
        //     $product->colors = json_encode($request->colors);
        // } else {
        //     $colors = [];
        //     $product->colors = json_encode($colors);
        // }
        $choice_options = [];
        if ($request->has('choice')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;
                $item['name'] = 'choice_'.$no;
                $item['title'] = $request->choice[$key];
                $item['options'] = explode(',', implode('|', $request[$str]));
                array_push($choice_options, $item);
            }
        }
        $product->choice_options = json_encode($choice_options);
        //combinations start
        $options = [];
        // if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
        //     $colors_active = 1;
        //     array_push($options, $request->colors);
        // }
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        //Generates the combinations of customer choice options
        $combinations = Helpers::combinations($options);
        $variations = [];
        $stock_count = 0;
        $stock_count = (int) $request['total'];

        $variations = [];
        $stock_count = 0;
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $k => $item) {
                    if ($k > 0) {
                        $str .= '-'.str_replace(' ', '', $item);
                    } else {
                        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
                            $color_name = Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                $item = [];
                $item['type'] = $str;
                $item['price'] = BackEndHelper::currency_to_usd(abs($request['price_'.str_replace('.', '_', $str)]));
                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                $item['qty'] = abs($request['qty_'.str_replace('.', '_', $str)]);
                array_push($variations, $item);
                $stock_count += $item['qty'];
            }
        } else {
            $stock_count = (int) $request['current_stock'];
        }

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        //combinations end
        $product->variation = json_encode($variations);
        $product->unit_price = Convert::usd($request->unit_price);
        $product->purchase_price = Convert::usd($request->unit_price);
        $product->tax = $request->tax;
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount_type == 'flat' ? Convert::usd($request->discount) : $request->discount;
        $product->discount_type = $request->discount_type;
        $product->request_status = 1;
        $product->attributes = json_encode($request->choice_attributes);

        $product->total = $request['total'];
        $product->size = $request['size'];

        // $product->meta_title = $request->meta_title;
        // $product->meta_description = $request->meta_description;
        // $product->meta_image = ImageManager::upload('product/meta/', 'png', $request->meta_image);

        // $product->video_provider = 'youtube';
        // $product->video_url = $request->video_link;
        $product->status = 0;

        // dd($request);

        if ($request->ajax()) {
            return response()->json([], 200);
        } else {
            $current = [];
            foreach ($room as $r) {
                if ($r) {
                    $isi = new Detail_room();
                    $isi->name = $r->nomor;
                    $isi->room_id = $rooms_id;
                    if ($r->isi == 1) {
                        $avai = 0;
                    } else {
                        $avai = 1;
                        array_push($current, 1);
                    }
                    $isi->available = $avai;
                }
                $isi->save();
            }
            $product->current_stock = count($current);
            $product->save();
            $data = [];
            // foreach ($request->lang as $index => $key) {
            //     if ($request->name[$index] && $key != 'en') {
            //         array_push($data, [
            //             'translationable_type' => 'App\Model\Product',
            //             'translationable_id' => $product->id,
            //             'locale' => $key,
            //             'key' => 'name',
            //             'value' => $request->name[$index],
            //         ]);
            //     }
            //     if ($request->description[$index] && $key != 'en') {
            //         array_push($data, [
            //             'translationable_type' => 'App\Model\Product',
            //             'translationable_id' => $product->id,
            //             'locale' => $key,
            //             'key' => 'description',
            //             'value' => $request->description[$index],
            //         ]);
            //     }
            // }
            Translation::insert($data);
            Toastr::success('Kamar berhasil ditambahkan!');

            return redirect()->route('admin.product.list', ['type' => 'in_house']);
        }
    }

    public function list(Request $request, $type)
    {
        $query_param = [];
        $search = $request['search'];
        if ($type == 'in_house') {
            $pro = Product::where(['added_by' => 'admin']);
        } else {
            $pro = Product::where(['added_by' => 'seller'])->where('request_status', $request->status);
        }

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $pro = $pro->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }

        $request_status = $request['status'];
        $products = $pro->orderBy('id', 'DESC')->paginate(Helpers::pagination_limit())->appends(['status' => $request['status']])->appends($query_param);

        return view('admin-views.product.list', compact('products', 'search', 'request_status'));
    }

    public function status_update(Request $request)
    {
        $product = Product::where(['id' => $request['id']])->first();
        $success = 1;
        if ($request['status'] == 1) {
            if ($product->added_by == 'seller' && $product->request_status == 0) {
                $success = 0;
            } else {
                $product->status = $request['status'];
            }
        } else {
            $product->status = $request['status'];
        }
        $product->save();

        return response()->json([
            'success' => $success,
        ], 200);
    }

    public function room_update(Request $request)
    {
        $room = Detail_room::where(['id' => $request['id']])->first();
        $product = Product::where('room_id', $room->room_id)->first();
        $success = 1;

        $room->available = $request['status'];

        $room->save();
        $current = Detail_room::where('room_id', $room['room_id'])->where('available', 1)->get();
        $available = count($current);
        $product->current_stock = $available;

        $product->save();

        return response()->json([
            'success' => $success,
        ], 200);
    }

    public function get_categories(Request $request)
    {
        $cat = Category::where(['parent_id' => $request->parent_id])->get();
        $res = '<option value="'. 0 .'" disabled selected>---Select---</option>';
        foreach ($cat as $row) {
            if ($row->id == $request->sub_category) {
                $res .= '<option value="'.$row->id.'" selected >'.$row->name.'</option>';
            } else {
                $res .= '<option value="'.$row->id.'">'.$row->name.'</option>';
            }
        }

        return response()->json([
            'select_tag' => $res,
        ]);
    }

    public function sku_combination(Request $request)
    {
        $options = [];
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = Helpers::combinations($options);

        return response()->json([
            'view' => view('admin-views.product.partials._sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'))->render(),
        ]);
    }

    public function edit($id)
    {
        $product = Product::withoutGlobalScopes()->with('translations', 'kost')->find($id);
        $fas = Fasilitas::where('tipe', 'kamar')->get();
        $kost = Kost::where('added_by', 'admin')->get();
        $fasi = json_decode($product->fasilitas_id);

        return view('admin-views.product.edit', compact('fasi', 'kost', 'fas', 'product'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kost_id' => 'required',
            'type' => 'required',
            'fasilitas' => 'required',
            'size' => 'required',
            'tax' => 'required|min:0',
            'unit_price' => 'required|numeric|min:1',
        ], [
            'kost_id.required' => 'Mohon pilih property yang akan ditambahkan!',
            'type.required' => 'Mohon pilih tipe kamar!',
            'unit_price.required' => 'Mohon masukan harga sewa!',
        ]);

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['unit_price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['unit_price'] <= $dis) {
            $validator->after(function ($validator) {
                $validator->errors()->add('unit_price', 'Discount can not be more or equal to the price!');
            });
        }

        $product = Product::find($id);

        // dd($request);
        $product->kost_id = $request->kost_id;
        $product->fasilitas_id = json_encode($request->fasilitas);
        $product->type = $request->type;
        $product->size = $request->size;
        $product_images = json_decode($product->images);

        $choice_options = [];
        if ($request->has('choice')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;
                $item['name'] = 'choice_'.$no;
                $item['title'] = $request->choice[$key];
                $item['options'] = explode(',', implode('|', $request[$str]));
                array_push($choice_options, $item);
            }
        }
        $product->choice_options = json_encode($choice_options);
        $variations = [];
        //combinations start
        $options = [];
        // if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
        //     $colors_active = 1;
        //     array_push($options, $request->colors);
        // }
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        //Generates the combinations of customer choice options
        $combinations = Helpers::combinations($options);
        $variations = [];
        $stock_count = 0;
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $k => $item) {
                    if ($k > 0) {
                        $str .= '-'.str_replace(' ', '', $item);
                    } else {
                        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
                            $color_name = Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                $item = [];
                $item['type'] = $str;
                $item['price'] = BackEndHelper::currency_to_usd(abs($request['price_'.str_replace('.', '_', $str)]));
                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                $item['qty'] = abs($request['qty_'.str_replace('.', '_', $str)]);
                array_push($variations, $item);

                $stock_count += $item['qty'];
            }
        } else {
            $stock_count = (int) $request['current_stock'];
        }
        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }

        if ($request->file('images')) {
            foreach ($request->file('images') as $img) {
                $product_images[] = ImageManager::upload('product/', 'png', $img);
            }
            $product->images = json_encode($product_images);
        }

        //combinations end
        $product->variation = json_encode($variations);
        $product->unit_price = BackEndHelper::currency_to_usd($request->unit_price);
        $product->attributes = json_encode($request->choice_attributes);
        $product->purchase_price = BackEndHelper::currency_to_usd($request->unit_price);
        $product->tax = $request->tax == 'flat' ? BackEndHelper::currency_to_usd($request->tax) : $request->tax;
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount_type == 'flat' ? BackEndHelper::currency_to_usd($request->discount) : $request->discount;
        $product->discount_type = $request->discount_type;

        if ($request->ajax()) {
            return response()->json([], 200);
        } else {
            $product->save();

            Toastr::success('Kamar berhasil diubah.');

            return back();
        }
    }

    public function remove_image(Request $request)
    {
        ImageManager::delete('/product/'.$request['image']);
        $product = Product::find($request['id']);
        $array = [];
        if (count(json_decode($product['images'])) < 2) {
            Toastr::warning('You cannot delete all images!');

            return back();
        }
        foreach (json_decode($product['images']) as $image) {
            if ($image != $request['name']) {
                array_push($array, $image);
            }
        }
        Product::where('id', $request['id'])->update([
            'images' => json_encode($array),
        ]);
        Toastr::success('Product image removed successfully!');

        return back();
    }

    public function delete($id)
    {
        $translation = Translation::where('translationable_type', 'App\Model\Product')
            ->where('translationable_id', $id);
        $translation->delete();
        $product = Product::find($id);
        foreach (json_decode($product['images'], true) as $image) {
            ImageManager::delete('/product/'.$image);
        }
        ImageManager::delete('/product/thumbnail/'.$product['thumbnail']);
        $product->delete();
        $detail = Detail_room::where('room_id', $product->room_id)->get();
        foreach ($detail as $d) {
            $d->delete();
        }
        FlashDealProduct::where(['product_id' => $id])->delete();
        DealOfTheDay::where(['product_id' => $id])->delete();
        Toastr::success('Product removed successfully!');

        return back();
    }

    public function bulk_import_index()
    {
        return view('admin-views.product.bulk-import');
    }

    public function bulk_import_data(Request $request)
    {
        try {
            $collections = (new FastExcel())->import($request->file('products_file'));
        } catch (\Exception $exception) {
            Toastr::error('You have uploaded a wrong format file, please upload the right file.');

            return back();
        }

        $data = [];
        $skip = ['youtube_video_url', 'details'];
        foreach ($collections as $collection) {
            foreach ($collection as $key => $value) {
                if ($value === '' && !in_array($key, $skip)) {
                    Toastr::error('Please fill '.$key.' fields');

                    return back();
                }
            }

            array_push($data, [
                'name' => $collection['name'],
                'slug' => Str::slug($collection['name'], '-').'-'.Str::random(6),
                'category_ids' => json_encode([['id' => $collection['category_id'], 'position' => 0], ['id' => $collection['sub_category_id'], 'position' => 1], ['id' => $collection['sub_sub_category_id'], 'position' => 2]]),
                'brand_id' => $collection['brand_id'],
                'unit' => $collection['unit'],
                'min_qty' => $collection['min_qty'],
                'refundable' => $collection['refundable'],
                'unit_price' => $collection['unit_price'],
                'purchase_price' => $collection['purchase_price'],
                'tax' => $collection['tax'],
                'discount' => $collection['discount'],
                'discount_type' => $collection['discount_type'],
                'current_stock' => $collection['current_stock'],
                'details' => $collection['details'],
                'video_provider' => 'youtube',
                'video_url' => $collection['youtube_video_url'],
                'images' => json_encode(['def.png']),
                'thumbnail' => 'def.png',
                'status' => 1,
                'request_status' => 1,
                'colors' => json_encode([]),
                'attributes' => json_encode([]),
                'choice_options' => json_encode([]),
                'variation' => json_encode([]),
                'featured_status' => 1,
                'added_by' => 'admin',
                'user_id' => auth('admin')->id(),
            ]);
        }
        DB::table('products')->insert($data);
        Toastr::success(count($data).' - Products imported successfully!');

        return back();
    }

    public function bulk_export_data()
    {
        $products = Product::where(['added_by' => 'admin'])->get();
        //export from product
        $storage = [];
        foreach ($products as $item) {
            $category_id = 0;
            $sub_category_id = 0;
            $sub_sub_category_id = 0;
            foreach (json_decode($item->category_ids, true) as $category) {
                if ($category['position'] == 1) {
                    $category_id = $category['id'];
                } elseif ($category['position'] == 2) {
                    $sub_category_id = $category['id'];
                } elseif ($category['position'] == 3) {
                    $sub_sub_category_id = $category['id'];
                }
            }
            $storage[] = [
                'name' => $item->name,
                'category_id' => $category_id,
                'sub_category_id' => $sub_category_id,
                'sub_sub_category_id' => $sub_sub_category_id,
                'brand_id' => $item->brand_id,
                'unit' => $item->unit,
                'min_qty' => $item->min_qty,
                'refundable' => $item->refundable,
                'youtube_video_url' => $item->video_url,
                'unit_price' => $item->unit_price,
                'purchase_price' => $item->purchase_price,
                'tax' => $item->tax,
                'discount' => $item->discount,
                'discount_type' => $item->discount_type,
                'current_stock' => $item->current_stock,
                'details' => $item->details,
            ];
        }

        return (new FastExcel($storage))->download('inhouse_products.xlsx');
    }
}
