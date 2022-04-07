<?php

namespace App\Http\Controllers\Web;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\OrderManager;
use App\CPU\ProductManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Brand;
use App\Model\BusinessSetting;
use App\Model\Cart;
use App\Model\CartShipping;
use App\Model\Category;
use App\Model\Contact;
use App\Model\DealOfTheDay;
use App\Model\FlashDeal;
use App\Model\FlashDealProduct;
use App\Model\HelpTopic;
use App\Model\Jobs;
use App\Model\Kampus;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Review;
use App\Model\Seller;
use App\Model\ShippingAddress;
use App\Model\Shop;
use App\Model\Wishlist;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Laravolt\Indonesia\Models\City;

class WebController extends Controller
{
    public function maintenance_mode()
    {
        $maintenance_mode = Helpers::get_business_settings('maintenance_mode') ?? 0;
        if ($maintenance_mode) {
            return view('web-views.maintenance-mode');
        }

        return redirect()->route('home');
    }

    public function home(Request $request)
    {
        CartManager::cart_clean();
        $home_categories = Category::where('home_status', true)->get();
        $home_categories->map(function ($data) {
            $data['products'] = Product::active()->whereJsonContains('category_ids', ['id' => (string) $data['id']])->inRandomOrder()->take(12)->get();
        });
        // dd($home_categories);
        //products based on top seller
        $top_sellers = Seller::approved()->with('shop')
            ->withCount(['orders'])->orderBy('orders_count', 'DESC')->take(15)->get();
        //end

        //feature products finding based on selling
        $featured_products = Product::with(['reviews'])->active()
            ->where('featured', 1)
            ->withCount(['order_details'])->orderBy('order_details_count', 'DESC')
            ->take(12)
            ->get();
        //end

        // pluck value with different value in relations
        $cities = Product::with('kost')->get()->pluck('kost.city', 'kost.city');
        $city = [];
        foreach ($cities as $c => $key) {
            $id = City::where('name', $c)->first();
            $str = ['kabupaten', 'kota '];
            $rpl = ['Kab.', ''];
            $low = strtolower($c);
            $cit = str_replace($str, $rpl, $low);

            $data = [
                'id' => $id->id,
                'name' => $cit,
            ];
            array_push($city, $data);
        }

        $kampus = Product::with('kost')->get();
        $ptnIds = [];
        foreach ($kampus as $k) {
            array_push($ptnIds, $k->kost->ptn_id);
        }
        $ptn = Kampus::with('city')->whereIn('id', array_unique($ptnIds))->get();
        // dd($ptn->get());

        // flash-deal sortby

        $article = BusinessSetting::where('type', 'article_footer')->first();

        $latest_products = Product::with(['reviews', 'kost'])->active()->orderBy('id', 'desc')->take(8)->get();
        $categories = Category::where('position', 0)->take(12)->get();
        $brands = Brand::take(15)->get();
        //best sell product
        $bestSellProduct = OrderDetail::with('product.reviews')
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('COUNT(product_id) as count'))
            ->groupBy('product_id')
            ->orderBy('count', 'desc')
            ->take(4)
            ->get();
        //Top rated
        $topRated = Review::with('product')
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('AVG(rating) as count'))
            ->groupBy('product_id')
            ->orderBy('count', 'desc')
            ->take(4)
            ->get();

        if ($bestSellProduct->count() == 0) {
            $bestSellProduct = $latest_products;
        }

        if ($topRated->count() == 0) {
            $topRated = $bestSellProduct;
        }
        $products = [];
        $filter = '';
        if ($request['sort'] == 'flash' && $request['city'] != '') {
            $kota = City::where('id', $request['city'])->first()->name;
            $flash_deals = FlashDeal::with(['products.product.reviews'])->where(['status' => 1])->where(['deal_type' => 'flash_deal'])->whereDate('start_date', '
                        <=', date('Y-m-d'))->whereDate('end_date', '>=', date('Y-m-d'))->first();
            $filter = $kota;
        } else {
            $flash_deals = FlashDeal::with(['products.product.reviews'])->where(['status' => 1])->where(['deal_type' => 'flash_deal'])->whereDate('start_date', '
                        <=', date('Y-m-d'))->whereDate('end_date', '>=', date('Y-m-d'))->first();
        }

        $deal_of_the_day = DealOfTheDay::join('products', 'products.id', '=', 'deal_of_the_days.product_id')->select('deal_of_the_days.*', 'products.unit_price')->where('deal_of_the_days.status', 1)->first();

        return view('web-views.home', compact('ptn', 'filter', 'flash_deals', 'article', 'city', 'featured_products', 'topRated', 'bestSellProduct', 'latest_products', 'categories', 'brands', 'deal_of_the_day', 'top_sellers', 'home_categories'));
    }

    public function flash_deals($id)
    {
        $deal = FlashDeal::with(['products.product.reviews'])->where(['id' => $id, 'status' => 1])->whereDate('start_date', '<=', date('Y-m-d'))->whereDate('end_date', '>=', date('Y-m-d'))->first();

        $discountPrice = FlashDealProduct::with(['product'])->whereHas('product', function ($query) {
            $query->active();
        })->get()->map(function ($data) {
            return [
                'discount' => $data->discount,
                'sellPrice' => $data->product->unit_price,
                'discountedPrice' => $data->product->unit_price - $data->discount,
            ];
        })->toArray();
        // dd($deal->toArray());

        if (isset($deal)) {
            return view('web-views.deals', compact('deal', 'discountPrice'));
        }
        Toastr::warning(translate('not_found'));

        return back();
    }

    public function search_shop(Request $request)
    {
        $key = explode(' ', $request['shop_name']);
        $sellers = Shop::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->whereHas('seller', function ($query) {
            return $query->where(['status' => 'approved']);
        })->paginate(30);

        return view('web-views.sellers', compact('sellers'));
    }

    public function all_categories()
    {
        $categories = Category::all();

        return view('web-views.categories', compact('categories'));
    }

    public function categories_by_category($id)
    {
        $category = Category::with(['childes.childes'])->where('id', $id)->first();

        return response()->json([
            'view' => view('web-views.partials._category-list-ajax', compact('category'))->render(),
        ]);
    }

    public function all_brands()
    {
        $brands = Brand::paginate(24);

        return view('web-views.brands', compact('brands'));
    }

    public function all_sellers()
    {
        $sellers = Shop::whereHas('seller', function ($query) {
            return $query->approved();
        })->paginate(24);

        return view('web-views.sellers', compact('sellers'));
    }

    public function seller_profile($id)
    {
        $seller_info = Seller::find($id);

        return view('web-views.seller-profile', compact('seller_info'));
    }

    public function searched_products(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Product name is required!',
        ]);

        $result = ProductManager::search_products($request['name']);
        $products = $result['products'];

        if ($products == null) {
            $result = ProductManager::translated_product_search($request['name']);
            $products = $result['products'];
        }

        // dd($result);

        return response()->json([
            'result' => view('web-views.partials._search-result', compact('products'))->render(),
        ]);
    }

    public function checkout_details(Request $request)
    {
        $cart_group_ids = CartManager::get_cart_group_ids();
        // if (CartShipping::whereIn('cart_group_id', $cart_group_ids)->count() != count($cart_group_ids)) {
        //     Toastr::info(translate('select_shipping_method_first'));

        //     return redirect('shop-cart');
        // }

        if (count($cart_group_ids) > 0) {
            // session(['address_changed' => 0]);
            return view('web-views.checkout-shipping');
        }

        Toastr::info(translate('no_items_in_basket'));

        return redirect('/');
    }

    public function checkout_payment(Request $request)
    {
        $cart_group_ids = CartManager::get_cart_group_ids();
        // if (CartShipping::whereIn('cart_group_id', $cart_group_ids)->count() != count($cart_group_ids)) {
        //     Toastr::info(translate('select_shipping_method_first'));

        //     return redirect('shop-cart');
        // }
        // dd($request);
        $order = Order::with('details', 'room')->where('id', $request['order_id'])->first();

        // if (session()->has('address_id') && count($cart_group_ids) > 0) {
        return view('web-views.checkout-payment', compact('order'));
        // }

        // Toastr::error(translate('incomplete_info'));

        // return back();
    }

    public function checkout_complete(Request $request)
    {
        // dd($request);
        $id = auth('customer')->user()->id;
        $user = User::find($id);
        $image = $request->file('ktp');

        if ($image != null) {
            $imageName = ImageManager::update('ktp/', auth('customer')->user()->ktp, 'png', $request->file('ktp'));
            $user->ktp = $imageName;
            $user->save();
        }

        $user = User::find($id);
        if ($user->ktp == null) {
            Toastr::warning('Mohon upload ktp anda');

            return redirect()->back();
        }

        $unique_id = OrderManager::gen_unique_id();
        $order_ids = [];
        foreach (CartManager::get_cart_group_ids() as $group_id) {
            $data = [
                'payment_method' => 'cash_on_delivery',
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'transaction_ref' => '',
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id,
                'data' => $request,
            ];
            $order_id = OrderManager::generate_order($data);
            array_push($order_ids, $order_id);
        }

        CartManager::cart_clean();

        return view('web-views.checkout-complete');
    }

    public function order_placed()
    {
        return view('web-views.checkout-complete');
    }

    public function shop_cart()
    {
        $cart = Cart::where('customer_id', auth('customer')->id())->orderby('id', 'DESC')->get();
        // dd($cart);
        // if (auth('customer')->check() && count($cart) > 0) {
        if (count($cart) > 0) {
            $order = Order::with('details')->where('customer_id', auth('customer')->id())->get();
            $product_id = $cart[0]->product_id;
            foreach ($order as $val) {
                // dd($val);
                $ord = $val->details[0]->product_id;
                if ($val->order_status != 'delivered' && $val->order_status != 'canceled' && $val->order_status != 'failed' && $val->order_status != 'expired') {
                    if ($product_id == $ord) {
                        Toastr::warning('Selesaikan proses booking sebelumnya dulu');

                        return redirect()->back();
                    }
                }
            }
            // dd($order, $cart);
            $user = auth('customer')->user();
            // dd($user);
            if ($user->kelamin == null && $user->lahir == null || $user->status_pernikahan == null) {
                Toastr::info('Mohon lengkapi data diri !');

                return redirect()->route('user-account');
            }
            // $address = ShippingAddress::where('customer_id', $user)->first();

            // dd($address);
            // session()->put('address_id', $address->id);

            return view('web-views.shop-cart');
        }

        Toastr::info(translate('no_items_in_basket'));

        // return redirect('/');
        return view('web-views.shop-cart');
    }

    //for seller Shop

    public function seller_shop(Request $request, $id)
    {
        $product_ids = Product::active()
            ->when($id == 0, function ($query) {
                return $query->where(['added_by' => 'admin']);
            })
            ->when($id != 0, function ($query) use ($id) {
                return $query->where(['added_by' => 'seller'])
                    ->where('user_id', $id);
            })
            ->pluck('id')->toArray();

        $avg_rating = Review::whereIn('product_id', $product_ids)->avg('rating');
        $total_review = Review::whereIn('product_id', $product_ids)->count();
        $total_order = OrderDetail::whereIn('product_id', $product_ids)->groupBy('order_id')->count();

        //finding category ids
        $products = Product::whereIn('id', $product_ids)->paginate(12);

        $category_info = [];
        foreach ($products as $product) {
            array_push($category_info, $product['category_ids']);
        }

        $category_info_decoded = [];
        foreach ($category_info as $info) {
            array_push($category_info_decoded, json_decode($info));
        }

        $category_ids = [];
        foreach ($category_info_decoded as $decoded) {
            foreach ($decoded as $info) {
                array_push($category_ids, $info->id);
            }
        }

        $categories = [];
        foreach ($category_ids as $category_id) {
            $category = Category::with(['childes.childes'])->where('position', 0)->find($category_id);
            if ($category != null) {
                array_push($categories, $category);
            }
        }
        $categories = array_unique($categories);
        //end

        //products search
        if ($request->product_name) {
            $products = Product::active()
                ->when($id == 0, function ($query) {
                    return $query->where(['added_by' => 'admin']);
                })
                ->when($id != 0, function ($query) use ($id) {
                    return $query->where(['added_by' => 'seller'])
                        ->where('user_id', $id);
                })
                ->where('name', 'like', $request->product_name.'%')
                ->paginate(12);
        } elseif ($request->category_id) {
            $products = Product::active()
                ->when($id == 0, function ($query) {
                    return $query->where(['added_by' => 'admin']);
                })
                ->when($id != 0, function ($query) use ($id) {
                    return $query->where(['added_by' => 'seller'])
                        ->where('user_id', $id);
                })
                ->whereJsonContains('category_ids', [
                    ['id' => strval($request->category_id)],
                ])->paginate(12);
        }

        if ($id == 0) {
            $shop = [
                'id' => 0,
                'name' => Helpers::get_business_settings('company_name'),
            ];
        } else {
            $shop = Shop::where('seller_id', $id)->first();
            if (isset($shop) == false) {
                Toastr::error(translate('shop_does_not_exist'));

                return back();
            }
        }

        return view('web-views.shop-page', compact('products', 'shop', 'categories'))
            ->with('seller_id', $id)
            ->with('total_review', $total_review)
            ->with('avg_rating', $avg_rating)
            ->with('total_order', $total_order);
    }

    //ajax filter (category based)
    public function seller_shop_product(Request $request, $id)
    {
        $products = Product::active()->with('shop')->where(['added_by' => 'seller'])
            ->where('user_id', $id)
            ->whereJsonContains('category_ids', [
                ['id' => strval($request->category_id)],
            ])
            ->paginate(12);
        $shop = Shop::where('seller_id', $id)->first();
        if ($request['sort_by'] == null) {
            $request['sort_by'] = 'latest';
        }

        if ($request->ajax()) {
            return response()->json([
                'view' => view('web-views.products._ajax-products', compact('products'))->render(),
            ], 200);
        }

        return view('web-views.shop-page', compact('products', 'shop'))->with('seller_id', $id);
    }

    public function quick_view(Request $request)
    {
        $product = ProductManager::get_product($request->product_id);
        $order_details = OrderDetail::where('product_id', $product->id)->get();
        $wishlists = Wishlist::where('product_id', $product->id)->get();
        $countOrder = count($order_details);
        $countWishlist = count($wishlists);
        $relatedProducts = Product::with(['reviews'])->where('category_ids', $product->category_ids)->where('id', '!=', $product->id)->limit(12)->get();

        return response()->json([
            'success' => 1,
            'view' => view('web-views.partials._quick-view-data', compact('product', 'countWishlist', 'countOrder', 'relatedProducts'))->render(),
        ]);
    }

    public function product($slug)
    {
        $product = Product::active()->with(['reviews'])->where('slug', $slug)->first();
        $auth = auth('customer')->id();
        if ($auth) {
            CartManager::cart_clean();
        }
        if ($product != null) {
            $countOrder = OrderDetail::where('product_id', $product->id)->count();
            $countWishlist = Wishlist::where('product_id', $product->id)->count();
            $relatedProducts = Product::with(['reviews'])->active()->where('category_ids', $product->category_ids)->where('id', '!=', $product->id)->limit(12)->get();
            $deal_of_the_day = DealOfTheDay::where('product_id', $product->id)->where('status', 1)->first();
            $img = json_decode($product->images);
            $kos = json_decode(($product->kost->images));
            foreach ($kos as $key => $val) {
                array_push($img, $val);
            }

            return view('web-views.products.details', compact('product', 'img', 'countWishlist', 'countOrder', 'relatedProducts', 'deal_of_the_day'));
        }

        Toastr::error(translate('not_found'));

        return back();
    }

    public function job($slug)
    {
        $product = Jobs::active()->where('slug', $slug)->first();
        $auth = auth('customer')->id();
        if ($auth) {
            CartManager::cart_clean();
        }
        if ($product != null) {
            return view('web-views.jobs.details', compact('product'));
        }

        Toastr::error(translate('not_found'));

        return back();
    }

    public function products(Request $request)
    {
        // dd($request);
        // session()->forget('search_name');
        $request['sort_by'] == null ? $request['sort_by'] == 'latest' : $request['sort_by'];

        $porduct_data = Product::active()->with(['reviews', 'kost']);
        $catId = $request['catId'];

        if ($request['type'] == 'catHome') {
            $cit = City::where('id', $request['city'])->first()->name;
            $products = $porduct_data->whereHas('kost', function ($q) use ($catId, $cit) {
                $q->where('category_id', '=', $catId)->where('city', '=', $cit);
            })->get();
            $product_ids = [];
            foreach ($products as $product) {
                // dd(json_decode($product['category_ids'], true));
                foreach (json_decode($product['category_ids'], true) as $category) {
                    array_push($product_ids, $product['id']);
                }
            }
            session()->put('search_name', $request['data_from'].' '.$cit);
            session()->put('collage', 0);
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'collage') {
            $city = $request['id'];
            $collage = Kampus::where('id', $city)->first()->name;
            // dd($collage);
            $details = Product::with('kost')->whereHas('kost', function ($q) use ($city) {
                $q->where('ptn_id', '=', $city);
            })->get();
            // dd($details);
            $product_ids = [];
            foreach ($details as $detail) {
                array_push($product_ids, $detail['id']);
            }
            session()->put('search_name', $collage);
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'city-filter') {
            $city = City::where('id', $request['city_id'])->first();
            $city_name = $city->name;
            $details = Product::with('kost')->whereHas('kost', function ($q) use ($city_name) {
                $q->where('city', '=', $city_name);
            })->get();
            $product_ids = [];
            foreach ($details as $detail) {
                array_push($product_ids, $detail['id']);
            }
            $kota = ['KOTA ', 'KABUPATEN'];
            $rpl = ['', 'Kab.'];

            $cit = str_replace($kota, $rpl, $city_name);
            session()->put('search_name', $cit);
            session()->put('collage', 0);
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'category') {
            $products = $porduct_data->get();
            $product_ids = [];
            foreach ($products as $product) {
                // dd(json_decode($product['category_ids'], true));
                foreach (json_decode($product['category_ids'], true) as $category) {
                    if ($category == $request['id']) {
                        array_push($product_ids, $product['id']);
                    }
                }
            }
            $nama_category = Category::where('id', $request['id'])->first();
            if ($nama_category) {
                session()->put('cat_name', $nama_category->name);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
            session()->put('collage', 0);
        }

        if ($request['data_from'] == 'brand') {
            $query = $porduct_data->where('brand_id', $request['id']);
            session()->put('collage', 0);
        }

        if ($request['data_from'] == 'latest') {
            $query = $porduct_data->orderBy('id', 'DESC');
            session()->put('collage', 0);
        }

        if ($request['data_from'] == 'top-rated') {
            $reviews = Review::select('product_id', DB::raw('AVG(rating) as count'))
                ->groupBy('product_id')
                ->orderBy('count', 'desc')->get();
            $product_ids = [];
            foreach ($reviews as $review) {
                array_push($product_ids, $review['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
            session()->put('collage', 0);
        }

        if ($request['data_from'] == 'best-selling') {
            $details = OrderDetail::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy('count', 'desc')
                ->get();
            $product_ids = [];
            foreach ($details as $detail) {
                array_push($product_ids, $detail['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
            session()->put('collage', 0);
        }

        if ($request['data_from'] == 'most-favorite') {
            $details = Wishlist::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy('count', 'desc')
                ->get();
            $product_ids = [];
            foreach ($details as $detail) {
                array_push($product_ids, $detail['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
            session()->put('collage', 0);
        }

        if ($request['data_from'] == 'featured') {
            $query = Product::with(['reviews'])->active()->where('featured', 1);
        }

        if ($request['data_from'] == 'search') {
            $key = $request['name'];
            $query = $porduct_data->whereHas('kost', function ($q) use ($key) {
                $q->where('name', 'like', "%{$key}%");
            });
            $products = $query->get();
            $data = [
                'id' => $request['id'],
                'name' => $request['name'],
                'data_from' => $request['data_from'],
                'sort_by' => $request['sort_by'],
                'page_no' => $request['page'],
                'min_price' => $request['min_price'],
                'max_price' => $request['max_price'],
            ];
            session()->put('collage', 0);
            if ($request->ajax()) {
                return response()->json([
                    'view' => view('web-views.products._ajax-products', compact('products'))->render(),
                ], 200);
            }
        }

        if ($request['sort_by'] == 'latest') {
            $fetched = $query->latest();
        } elseif ($request['sort_by'] == 'low-high') {
            $fetched = $query->orderBy('unit_price', 'ASC');
        } elseif ($request['sort_by'] == 'high-low') {
            $fetched = $query->orderBy('unit_price', 'DESC');
        } elseif ($request['sort_by'] == 'a-z') {
            $fetched = $query->orderBy('name', 'ASC');
        } elseif ($request['sort_by'] == 'z-a') {
            $fetched = $query->orderBy('name', 'DESC');
        } else {
            $fetched = $query;
        }

        if ($request['min_price'] != null || $request['max_price'] != null) {
            $fetched = $fetched->whereBetween('unit_price', [Helpers::convert_currency_to_usd($request['min_price']), Helpers::convert_currency_to_usd($request['max_price'])]);
        }

        $data = [
            'id' => $request['id'],
            'name' => $request['name'],
            'data_from' => $request['data_from'],
            'sort_by' => $request['sort_by'],
            'page_no' => $request['page'],
            'min_price' => $request['min_price'],
            'max_price' => $request['max_price'],
        ];

        $products = $fetched->paginate(20)->appends($data);

        if ($request->ajax()) {
            return response()->json([
                'view' => view('web-views.products._ajax-products', compact('products'))->render(),
            ], 200);
        }
        if ($request['data_from'] == 'category') {
            $name = Category::find((int) $request['id'])->name;
            session()->put('search_name', $name);
            session()->put('collage', 0);
        }
        if ($request['data_from'] == 'brand') {
            $data['brand_name'] = Brand::find((int) $request['id'])->name;
            session()->put('collage', 0);
        }

        $cities = Product::with('kost')->get()->pluck('kost.city', 'kost.city');
        $city = [];
        foreach ($cities as $c => $key) {
            $id = City::where('name', $c)->first();
            $str = ['kabupaten', 'kota '];
            $rpl = ['Kab.', ''];
            $low = strtolower($c);
            $cit = str_replace($str, $rpl, $low);

            $ci = [
                'id' => $id->id,
                'name' => $cit,
            ];
            array_push($city, $ci);
        }

        return view('web-views.products.view', compact('products', 'data', 'city'), $data);
    }

    public function jobs(Request $request)
    {
        // dd($request);
        // session()->forget('search_name');
        $request['sort_by'] == null ? $request['sort_by'] == 'latest' : $request['sort_by'];

        $porduct_data = Jobs::active();
        $catId = $request['catId'];

        if ($request['type'] == 'catHome') {
            $cit = City::where('id', $request['city'])->first()->name;
            $products = $porduct_data->whereHas('kost', function ($q) use ($catId, $cit) {
                $q->where('category_id', '=', $catId)->where('city', '=', $cit);
            })->get();
            $product_ids = [];
            foreach ($products as $product) {
                // dd(json_decode($product['category_ids'], true));
                foreach (json_decode($product['category_ids'], true) as $category) {
                    array_push($product_ids, $product['id']);
                }
            }
            session()->put('search_name', $request['data_from'].' '.$cit);
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'collage') {
            $city = $request['collage_id'];
            $collage = Kampus::where('id', $city)->first()->name;

            $details = Product::with('kost')->whereHas('kost', function ($q) use ($city) {
                $q->where('ptn_id', '=', $city);
            })->get();
            // dd($details);
            $product_ids = [];
            foreach ($details as $detail) {
                array_push($product_ids, $detail['id']);
            }
            // $kota = ['KOTA ', 'KABUPATEN'];
            // $rpl = ['', 'Kab.'];

            // $cit = str_replace($kota, $rpl, $city_name);
            session()->put('search_name', $collage);
            // dd($details);
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'city-filter') {
            $city = City::where('id', $request['city_id'])->first();
            $city_name = $city->name;
            $details = Product::with('kost')->whereHas('kost', function ($q) use ($city_name) {
                $q->where('city', '=', $city_name);
            })->get();
            $product_ids = [];
            foreach ($details as $detail) {
                array_push($product_ids, $detail['id']);
            }
            $kota = ['KOTA ', 'KABUPATEN'];
            $rpl = ['', 'Kab.'];

            $cit = str_replace($kota, $rpl, $city_name);
            session()->put('search_name', $cit);
            // dd($details);
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'category') {
            $products = $porduct_data->get();
            $product_ids = [];
            foreach ($products as $product) {
                // dd(json_decode($product['category_ids'], true));
                foreach (json_decode($product['category_ids'], true) as $category) {
                    if ($category == $request['id']) {
                        array_push($product_ids, $product['id']);
                    }
                }
            }
            $nama_category = Category::where('id', $request['id'])->first();
            if ($nama_category) {
                session()->put('cat_name', $nama_category->name);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'brand') {
            $query = $porduct_data->where('brand_id', $request['id']);
        }

        if ($request['data_from'] == 'latest') {
            $query = $porduct_data->orderBy('id', 'DESC');
        }

        if ($request['data_from'] == 'top-rated') {
            $reviews = Review::select('product_id', DB::raw('AVG(rating) as count'))
                ->groupBy('product_id')
                ->orderBy('count', 'desc')->get();
            $product_ids = [];
            foreach ($reviews as $review) {
                array_push($product_ids, $review['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'best-selling') {
            $details = OrderDetail::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy('count', 'desc')
                ->get();
            $product_ids = [];
            foreach ($details as $detail) {
                array_push($product_ids, $detail['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'most-favorite') {
            $details = Wishlist::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy('count', 'desc')
                ->get();
            $product_ids = [];
            foreach ($details as $detail) {
                array_push($product_ids, $detail['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'featured') {
            $query = Product::with(['reviews'])->active()->where('featured', 1);
        }

        if ($request['data_from'] == 'search') {
            $key = explode(' ', $request['name']);
            $query = $porduct_data->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
        }

        if ($request['sort_by'] == 'latest') {
            $fetched = $query->latest();
        } elseif ($request['sort_by'] == 'low-high') {
            $fetched = $query->orderBy('unit_price', 'ASC');
        } elseif ($request['sort_by'] == 'high-low') {
            $fetched = $query->orderBy('unit_price', 'DESC');
        } elseif ($request['sort_by'] == 'a-z') {
            $fetched = $query->orderBy('name', 'ASC');
        } elseif ($request['sort_by'] == 'z-a') {
            $fetched = $query->orderBy('name', 'DESC');
        } else {
            $fetched = $porduct_data;
        }

        if ($request['min_price'] != null || $request['max_price'] != null) {
            $fetched = $fetched->whereBetween('unit_price', [Helpers::convert_currency_to_usd($request['min_price']), Helpers::convert_currency_to_usd($request['max_price'])]);
        }

        $data = [
            'id' => $request['id'],
            'name' => $request['name'],
            'data_from' => $request['data_from'],
            'sort_by' => $request['sort_by'],
            'page_no' => $request['page'],
            'min_price' => $request['min_price'],
            'max_price' => $request['max_price'],
        ];

        $products = $fetched->paginate(20)->appends($data);

        if ($request->ajax()) {
            return response()->json([
                'view' => view('web-views.jobs._ajax-products', compact('products'))->render(),
            ], 200);
        }
        if ($request['data_from'] == 'category') {
            $name = Category::find((int) $request['id'])->name;
            session()->put('search_name', $name);
        }
        if ($request['data_from'] == 'brand') {
            $data['brand_name'] = Brand::find((int) $request['id'])->name;
        }

        $cities = Product::with('kost')->get()->pluck('kost.city', 'kost.city');
        $city = [];
        foreach ($cities as $c => $key) {
            $id = City::where('name', $c)->first();
            $str = ['kabupaten', 'kota '];
            $rpl = ['Kab.', ''];
            $low = strtolower($c);
            $cit = str_replace($str, $rpl, $low);

            $ci = [
                'id' => $id->id,
                'name' => $cit,
            ];
            array_push($city, $ci);
        }

        return view('web-views.jobs.view', compact('products', 'data', 'city'), $data);
    }

    public function viewWishlist()
    {
        $wishlists = Wishlist::with('product')->where('customer_id', auth('customer')->id())->get();

        return view('web-views.users-profile.account-wishlist', compact('wishlists'));
    }

    public function storeWishlist(Request $request)
    {
        if ($request->ajax()) {
            if (auth('customer')->check()) {
                $wishlist = Wishlist::where('customer_id', auth('customer')->id())->where('product_id', $request->product_id)->first();
                if (empty($wishlist)) {
                    $wishlist = new Wishlist();
                    $wishlist->customer_id = auth('customer')->id();
                    $wishlist->product_id = $request->product_id;
                    $wishlist->save();

                    $countWishlist = Wishlist::where('customer_id', auth('customer')->id())->get();
                    $data = 'Product has been added to wishlist';

                    $product_count = Wishlist::where(['product_id' => $request->product_id])->count();
                    session()->put('wish_list', Wishlist::where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray());

                    return response()->json(['success' => $data, 'value' => 1, 'count' => count($countWishlist), 'id' => $request->product_id, 'product_count' => $product_count]);
                } else {
                    $data = 'Product already added to wishlist';

                    return response()->json(['error' => $data, 'value' => 2]);
                }
            } else {
                $data = translate('login_first');

                return response()->json(['error' => $data, 'value' => 0]);
            }
        }
    }

    public function deleteWishlist(Request $request)
    {
        Wishlist::where(['product_id' => $request['id'], 'customer_id' => auth('customer')->id()])->delete();
        $data = 'Product has been remove from wishlist!';
        $wishlists = Wishlist::where('customer_id', auth('customer')->id())->get();
        session()->put('wish_list', Wishlist::where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray());

        return response()->json([
            'success' => $data,
            'count' => count($wishlists),
            'id' => $request->id,
            'wishlist' => view('web-views.partials._wish-list-data', compact('wishlists'))->render(),
        ]);
    }

    //for HelpTopic
    public function helpTopic()
    {
        $helps = HelpTopic::Status()->latest()->get();

        return view('web-views.help-topics', compact('helps'));
    }

    //for Contact US Page
    public function contacts()
    {
        return view('web-views.contacts');
    }

    public function about_us()
    {
        $about_us = BusinessSetting::where('type', 'about_us')->first();

        return view('web-views.about-us', [
            'about_us' => $about_us,
        ]);
    }

    public function termsandCondition()
    {
        $terms_condition = BusinessSetting::where('type', 'terms_condition')->first();

        return view('web-views.terms', compact('terms_condition'));
    }

    public function privacy_policy()
    {
        $privacy_policy = BusinessSetting::where('type', 'privacy_policy')->first();

        return view('web-views.privacy-policy', compact('privacy_policy'));
    }

    //order Details

    public function orderdetails()
    {
        return view('web-views.orderdetails');
    }

    public function chat_for_product(Request $request)
    {
        return $request->all();
    }

    public function supportChat()
    {
        return view('web-views.users-profile.profile.supportTicketChat');
    }

    public function error()
    {
        return view('web-views.404-error-page');
    }

    public function contact_store(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ], [
            'mobile_number.required' => 'Mobile Number is Empty!',
            'subject.required' => ' Subject is Empty!',
            'message.required' => 'Message is Empty!',
        ]);
        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->mobile_number = $request->mobile_number;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();
        Toastr::success(translate('Your Message Send Successfully'));

        return back();
    }
}
