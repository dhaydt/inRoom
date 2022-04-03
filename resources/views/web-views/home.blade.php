{{-- home.blade --}}

@extends('layouts.front-end.app')

@section('title','Welcome To '. $web_config['name']->value.' Home')

@push('css_or_js')
<meta property="og:image" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}" />
<meta property="og:title" content="Welcome To {{$web_config['name']->value}} Home" />
<meta property="og:url" content="{{env('APP_URL')}}">
<meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

<meta property="twitter:card" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}" />
<meta property="twitter:title" content="Welcome To {{$web_config['name']->value}} Home" />
<meta property="twitter:url" content="{{env('APP_URL')}}">
<meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">

<link rel="stylesheet" href="{{asset('public/assets/front-end')}}/css/home.css" />
<style>
    #navbarCollapse > ul.navbar-nav.mega-nav.pr-2.pl-2.mr-2.d-none.d-xl-block > li > a {
        pointer-events: none;
    }
    #navbarCollapse > ul.navbar-nav.mega-nav.pr-2.pl-2.mr-2.d-none.d-xl-block > li > ul {
        display: block !important;
    }
    .manual-nav.nav-manual .owl-carousel .owl-nav  {
        display: flex;
        position: absolute;
        top: -84px;
        color: {{ $web_config['primary_color'] }};
        right: -25px;
    }
    .manual-nav.nav-manual .owl-carousel .owl-nav button {
        background-color: #cbcbcb;
        padding: 5px 11px !important;
        border-radius: 50%;
    }
    .container {
        max-width: 1138px;
    }
    .media {
        background: white;
    }
    .section-header {
        display: flex;
        text-transform: capitalize;
        justify-content: space-between;
    }
    .label-kost{
        background-color: #24b400;
    }
    .feature_header span.capitalize{
        text-transform: capitalize;
        background-color: transparent !important;
    }
    .cz-countdown {
        display: inline-block;
        flex-wrap: wrap;
        margin-top: 0 !important;
        font-weight: normal;
        font-size: smaller;
        }
    .cz-countdown-days {
        color: white !important;
        background-color: #f15151;
        padding: 3px 6px;
        border-radius: 3px;
        margin-right: 3px !important;
        font-weight: 700;
    }

    .cz-countdown-hours {
        color: white !important;
        background-color: #f15151;
        padding: 3px 6px;
        border-radius: 3px;
        margin-right: 3px !important;
    }

    .cz-countdown-minutes {
        color: white !important;
        background-color: #f15151;
        padding: 3px 6px;
        border-radius: 3px;
        margin-right: 3px !important;
    }

    .cz-countdown-seconds {
        color: #f15151;
        border: 1px solid #f15151;
        padding: 3px 6px;
        border-radius: 3px !important;
    }
    .flash_deal_product_details .flash-product-price {
        font-weight: 700;
        font-size: 18px;
        color: {{$web_config['primary_color']}};
    }
    .featured_deal_left {
        height: 130px;
        background: {{$web_config['primary_color']}} 0% 0% no-repeat padding-box;
        padding: 10px 100px;
        text-align: center;
    }
    .featured_deal {
        min-height: 130px;
    }
    .category_div:hover {
        color: {{$web_config['secondary_color']}};
    }
    .deal_of_the_day {
        /* filter: grayscale(0.5); */
        opacity: .8;
        background: {{$web_config['secondary_color']}};
        border-radius: 3px;
    }
    .deal-title {
        font-size: 12px;
    }
    .for-flash-deal-img img {
        max-width: none;
    }
    .kampus-body h5{
        font-size: 16px;
        font-weight: 600;
    }
    .kampus-body span {
        font-size: 16px;
        font-weight: 500;
    }

    @media (max-width: 375px) {
        .cz-countdown {
        display: flex !important;
        }
        .cz-countdown .cz-countdown-seconds {
        margin-top: -5px !important;
        }
        .for-feature-title {
        font-size: 20px !important;
        }
    }

    @media (max-width: 600px) {
        .selectLoc{
            font-size: 12px;
            color: {{ $web_config['primary_color'] }} !important;
        }
        .kampus-body h5{
        font-size: 14px;
        }
        .kampus-body span {
            font-size: 14px;
        }
        .manual-nav.nav-manual .owl-carousel .owl-nav button {
            padding: 5px 9px !important;
            font-size: 13px;
        }
        .manual-nav.nav-manual .owl-carousel .owl-nav{
            right: -7px;
            top: -57px;
        }
        .flash_deal_title {
        font-weight: 600;
        font-size: 18px;
        text-transform: uppercase;
        }
        .cz-countdown .cz-countdown-value {
        font-family: "Roboto", sans-serif;
        font-size: 11px !important;
        font-weight: 700 !important;
        }
        .featured_deal {
        opacity: 1 !important;
        }
        .cz-countdown {
        display: inline-block;
        flex-wrap: wrap;
        margin-top: 0;
        font-weight: normal;
        font-size: smaller;
        }
        .view-btn-div-f {
        /* margin-top: 6px; */
        float: right;
        }
        .view-btn-div {
        float: right;
        }
        .viw-btn-a {
        font-size: 10px;
        font-weight: 600;
        }
        .for-mobile {
        display: none;
        }
        .featured_for_mobile {
        max-width: 100%;
        margin-top: 20px;
        margin-bottom: 20px;
        }
    }

    @media (max-width: 360px) {
        .featured_for_mobile {
        max-width: 100%;
        margin-top: 10px;
        margin-bottom: 10px;
        }
        .featured_deal {
        opacity: 1 !important;
        }
    }

    @media (max-width: 375px) {
        .featured_for_mobile {
        max-width: 100%;
        margin-top: 10px;
        margin-bottom: 10px;
        }
        .featured_deal {
        opacity: 1 !important;
        }
    }

    @media (max-width: 992px) {
        .navbar-collapse {
        position: fixed;
        top: 56px;
        left: 0;
        padding-left: 15px;
        padding-right: 15px;
        padding-bottom: 15px;
        width: 89%;
        height: 100%;
        z-index: 99;
        }
        .navbar-collapse.collapsing {
        left: -75%;
        transition: height 0s ease;
        }
        .navbar-collapse.show {
        left: 0;
        background-color: #fff;
        transition: left 300ms ease-in-out;
        }
        .navbar-toggler.collapsed~.navbar-collapse {
        transition: left 500ms ease-in-out;
        }
    }

    @media (min-width: 768px) {
        .nav-item.dropdown.ml-auto {
            margin-left: 0px !important;
        }
        .timer {
            margin: 0 auto;
        }
        .timer .view_all .px-2 .cz-countdown {
            margin-left: 0 !important;
        }
        .displayTab {
        display: block !important;
        }
    }

    @media (max-width: 800px) {
        .for-tab-view-img {
        width: 40%;
        }
        .for-tab-view-img {
        width: 105px;
        }
        .widget-title {
        font-size: 19px !important;
        }
    }

    .featured_deal_carosel .carousel-inner {
        width: 100% !important;
    }
    .badge-style2 {
        color: black !important;
        background: transparent !important;
        font-size: 11px;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
  integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g=="
  crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
  integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw=="
  crossorigin="anonymous" referrerpolicy="no-referrer" />

@endpush

@section('content')
<!-- Search bar-->
<div class="search-box-home py-2 mt-2 d-none d-md-block">
    <div class="container">
        <div class="home-top-search-section">
            <h1 class="home-top-search-title mb-0">Mau cari kos?</h1>
            <span class="home-top-search-subtitle">Dapatkan info lengkap dan langsung sewa di INROOM</span>
        </div>
        <div class="input-group-overlay d-none d-md-block" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left' }}">
            <form action="{{route('products')}}" type="submit" class="search_form">
                <input class="px-5 form-control appended-form-control search-bar-input" id="search-bar" type="text" autocomplete="off"
                    placeholder="{{\App\CPU\translate('Masukan nama Lokasi / Area / Alamat')}}" name="name"
                    style="border: 2px solid #c9c9c9; border-radius: 50px; border-top-right-radius: 50px !important; border-bottom-right-radius: 50px !important;">
                <button disabled class="input-group-append-overlay search-icon"
                    style="border-radius: {{Session::get('direction') === " rtl" ? '0px 50px 50px 0px; left: unset; right: 0' : '50px 0px 0px 50px; right: unset; left: 0'}};">
                    <span class="input-group-text" style="font-size: 20px;">
                    <i class="czi-search text-white"></i>
                    </span>
                </button>
                <button class="input-group-append-overlay search_button" type="submit"
                    style="border-radius: {{Session::get('direction') === " rtl" ? '50px 0px 0px 50px; right: unset; left: 0'
                    : '0px 50px 50px 0px; left: unset; right: 0' }};">
                    <span class="input-group-text text-white" style="font-size: 17px;">
                        {{ \App\CPU\translate('Cari') }}
                    </span>
                </button>
                <input name="data_from" value="search" hidden>
                <input name="page" value="1" hidden>
                <diV class="card search-card"
                    style="position: absolute;background: white;z-index: 999;width: 100%;display: none">
                    <div class="card-body search-result-box" style="overflow:scroll; height:400px;overflow-x: hidden"></div>
                </diV>
            </form>
        </div>
    </div>
</div>
<!-- end search -->

<!-- Hero (Banners + Slider)-->
<section class="bg-transparent">
    <div class="container">
        <div class="row ">
            <div class="col-12">
                @include('web-views.partials._home-banner')
            </div>
        </div>
    </div>
</section>
<!-- end Hero (Banners + Slider)-->

<!--categries-->
    <section class="container rtl" style="margin-top: -16px;">
        <!-- Heading-->
        <div class="section-header">
            <div class="feature_header">
                <span class="capitalize">{{ \App\CPU\translate('Mau_cari_apa')}} ?</span>
            </div>
        </div>

        <div class="mt-2 mb-3">
            <div class="owl-carousel owl-theme " id="category-slider">
                @foreach($categories as $category)
                    <div class="category_div p-0" style="height: 132px; width: 100%;">
                        <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">
                            <img style="vertical-align: middle; height: 88px"
                                onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                src="{{asset("storage/category/$category->icon")}}"
                                alt="{{$category->name}}">
                                    <p class="text-center small mt-2"
                                        style="">{{Str::limit($category->name, 17)}}</p>
                        </a>
                    </div>
                @endforeach
                <div class="category_div p-0" style="height: 132px; width: 100%;">
                    <a href="{{ route('jobs') }}">
                        <img style="vertical-align: middle; height: 88px"
                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                            src="{{ asset('assets/front-end/img/job.jpg') }}"
                            alt="loker">
                                <p class="text-center small mt-2"
                                    style="">Loker</p>
                    </a>
                </div>
            </div>
        </div>
    </section>
<!--end categries-->

<!--flash deal-->

{{-- {{ dd(request()->route()->parameters) }} --}}
  @if (isset($flash_deals))
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="section-header mb-2  rtl row justify-content-between">
          <div class="col-md-12 d-flex flashdeal-header">
            <div class="d-inline-flex displayTab">
              <span class="flash_deal_title capitalize">
                {{$flash_deals['title']}}
              </span>
            </div>
          </div>
          <div class="col-12 timer row" style="padding-{{Session::get('direction') === " rtl"
            ? 'left' : 'right' }}: 0">
            <div class="col-md-6 my-auto">
                <div class="view-btn-div-f w-100 flash-view">
                    <div class="d-md-flex align-items-center">
                      <span class="flashsale-label mb-0 mr-2 my-auto">{{\App\CPU\translate('berakhir_dalam')}} :</span>
                      <span class="cz-countdown"
                        data-countdown="{{isset($flash_deals)?date('m/d/Y',strtotime($flash_deals['end_date'])):''}} 11:59:00 PM">
                        <span class="cz-countdown-days">
                          <span class="cz-countdown-value"></span> {{ \App\CPU\translate('hari') }}
                        </span>
                        <span class="cz-countdown-value">:</span>
                        <span class="cz-countdown-hours">
                          <span class="cz-countdown-value"></span>
                        </span>
                        <span class="cz-countdown-value">:</span>
                        <span class="cz-countdown-minutes">
                          <span class="cz-countdown-value"></span>
                        </span>
                        <span class="cz-countdown-value">:</span>
                        <span class="cz-countdown-seconds">
                          <span class="cz-countdown-value"></span>
                        </span>
                      </span>
                    </div>
                  </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex flex-wrap float-right for-shoting-mobile">
                    <form id="flash-form" action="{{ route('home') }}" method="GET">
                        <div class="form-inline flex-nowrap for-mobile" style="margin-right: 70px;">
                            <label
                                class="opacity-75 text-nowrap {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}} for-shoting"
                                for="sorting">
                                <span
                                class="{{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}">{{\App\CPU\translate('Promo_di')}}</span></label>
                                <form action="{{ route('home') }}" method="get">
                                    <input type="hidden" value="flash" name="sort">
                                <select class="form-control custom-select capitalize" onchange="flashSubmit()" name="city" style="border: none;
                                background: transparent;
                                color: red;
                                padding: 5px;
                                cursor: pointer;
                                width: 110px;
                                text-overflow: ellipsis;
                                flex-wrap: nowrap;">
                                    <option class="text-dark" value="">Semua</option>
                                    @foreach ($city as $c)
                                        <option class="text-dark capitalize" value="{{ $c['id'] }}"{{ request()->city == $c['id'] ? 'selected' : '' }}>{{ $c['name'] }}</option>
                                    @endforeach
                                </select>
                                </form>
                        </div>
                    </form>
                </div>
            </div>
          </div>
        </div>
        <div id="flash-card" class="manual-nav nav-manual">
            @include('web-views.partials._flash-deal')
        </div>
      </div>
    </div>
  </div>
  @endif
  <!-- end flash deal -->


<!-- Filter city -->
    <section class="container rtl mt-3">
        <div class="section-header">
            <div class="feature_header">
                <span class="capitalize">{{ \App\CPU\translate('Cari_kos_di_kota_lain')}}</span>
            </div>
        </div>
        <div class="subtitle-city">
            <small class="text-grey">Temukan penyewaan Kos, Apartemen dan Ruko di Kota lain</small>
        </div>
        <div class="mt-2 mb-3">
            @foreach($city as $c)
            <div class="badge label-city capitalize">
                <form id="form-{{ $c['id'] }}" action="{{route('products')}}" type="submit" class="d-flex w-100">
                    <input type="hidden" name="data-from" value="city-filter" >
                    <input type="hidden" name="page" value="1" >
                    <input type="hidden" name="city_id" value="{{ $c['id'] }}">
                    <input type="submit" class="submit-city" value="{{ $c['name'] }}" />
                </form>
            </div>
            @endforeach
        </div>
    </section>
<!-- end Filter city -->

<!--
  {{--deal of the day--}}
  <div class="new-section my-4">
    <div class="container">
      <div class="row">
        {{-- Deal of the day/Recommended Product --}}
        <div class="col col-md-3 col-sm-6 col-12 day-off d-none">
        <div class="deal_of_the_day">
                    @if(isset($deal_of_the_day))
                        <h1 style="color: white"> {{ \App\CPU\translate('deal_of_the_day') }}</h1>
                        <center>
                            <strong style="font-size: 21px!important;color: {{$web_config['primary_color']}}">
                                {{$deal_of_the_day->discount_type=='amount'?\App\CPU\Helpers::currency_converter($deal_of_the_day->discount):$deal_of_the_day->discount.' % '}}
                                {{\App\CPU\translate('off')}}
                            </strong>
                        </center>
                        <div class="d-flex justify-content-center align-items-center" style="padding-top: 37px">
                            <img style="height: 206px;"
                                 src="{{\App\CPU\ProductManager::product_image_path('product')}}/{{json_decode($deal_of_the_day->product['images'])[0]}}"
                                 onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                 alt="">
                        </div>
                        <div style="text-align: center; padding-top: 26px;">
                            <h5 style="font-weight: 600; color: {{$web_config['primary_color']}}">
                                {{\Illuminate\Support\Str::limit($deal_of_the_day->product['name'],40)}}
                            </h5>
                            <span class="text-accent">
                                {{\App\CPU\Helpers::currency_converter(
                                    $deal_of_the_day->product->unit_price-(\App\CPU\Helpers::get_product_discount($deal_of_the_day->product,$deal_of_the_day->product->unit_price))
                                )}}
                            </span>
                            @if($deal_of_the_day->product->discount > 0)
                                <strike style="font-size: 12px!important;color: grey!important;">
                                    {{\App\CPU\Helpers::currency_converter($deal_of_the_day->product->unit_price)}}
                                </strike>
                            @endif

                        </div>
                        <div class="pt-3 pb-2" style="text-align: center;">
                            <button class="buy_btn"
                                    onclick="location.href='{{route('product',$deal_of_the_day->product->slug)}}'">{{\App\CPU\translate('buy_now')}}
                            </button>
                        </div>
                    @else
                        @php($product=\App\Model\Product::active()->inRandomOrder()->first())
                        @if(isset($product))
                            <h1 style="color: white"> {{ \App\CPU\translate('recommended_product') }}</h1>
                            <div class="d-flex justify-content-center align-items-center" style="padding-top: 55px">
                                <img style="height: 206px;"
                                     src="{{\App\CPU\ProductManager::product_image_path('product')}}/{{json_decode($product['images'])[0]}}"
                                     onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                     alt="">
                            </div>
                            <div style="text-align: center; padding-top: 60px;" class="pb-2">
                                <button class="buy_btn" onclick="location.href='{{route('product',$product->slug)}}'">
                                    {{\App\CPU\translate('buy_now')}}
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
                  <div class="container mt-2">
            <div class="row p-0">
              <div class="col-md-3 col-sm-6 col-6 mb-2 p-0 text-center mobile-padding">
                <img style="height: 29px;" src="{{asset("/public/assets/front-end/png/delivery.png")}}" alt="">
                <div class="deal-title">3 Days <br><span>Fast Delivery</span></div>
              </div>

              <div class="col-md-3 col-sm-6 col-6 mb-2 p-0 text-center">
                <img style="height: 29px;" src="{{asset("/public/assets/front-end/png/money.png")}}" alt="">
                <div class="deal-title">Money Back <br><span>Gurrantey</span></div>
              </div>
              <div class="col-md-3 col-sm-6 col-6 mb-2 p-0 text-center">
                <img style="height: 29px;" src="{{asset("/public/assets/front-end/png/Genuine.png")}}" alt="">
                <div class="deal-title">100% Genuine<br><span>Product</span></div>
              </div>
              <div class="col-md-3 col-sm-6 col-6 mb-2 p-0 text-center">
                <img style="height: 29px;" src="{{asset("/public/assets/front-end/png/Payment.png")}}" alt="">
                <div class="deal-title">Authentic<br><span>Payment</span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col col-md-12 col-sm-6 col-12">
          <div class="row">

            {{-- Latest products --}}
            <div class="col col-md-12 col-sm-6 col-12 latest card p-2" style="box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);
            -webkit-box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);
            -moz-box-shadow: 6px 8px 10px -10px rgba(0,0,0,0.25);">
              <div class="container rtl">
                <div class="section-header">
                  <div class="feature_header">
                    <span class="for-feature-title">{{ \App\CPU\translate('latest_Rooms')}}</span>
                  </div>
                  <div>
                    <a class="btn btn-sm viw-btn-a"
                      href="{{route('products',['data_from'=>'latest'])}}">
                      {{ \App\CPU\translate('view_all')}}
                    </a>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="row mt-2 mb-3 w-100">
                  @foreach($latest_products->slice(0, 6) as $product)
                  <div class="col-md-3 col-sm-6 col-6 mb-2">
                    @include('web-views.partials._single_product_featured',['product'=>$product])
                  </div>
                  @endforeach
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
-->

  @php($featured_deals=\App\Model\FlashDeal::with(['products.product.reviews'])->where(['status'=>1])->where(['deal_type'=>'feature_deal'])->first())

  @if(isset($featured_deals))
  <section class="container">
    <div class="row">
      <div class="col-xl-12">
        <div class="featured_deal">
          <div class="row">
            <div class="col-xl-3 col-md-4 rtl">
              <div class="d-flex align-items-center justify-content-center featured_deal_left">
                <h1 class="featured_deal_title">{{ \App\CPU\translate('featured_deal')}}</h1>
              </div>
            </div>
            <div class="col-xl-9 col-md-8">
              <div class="owl-carousel owl-theme" id="web-feature-deal-slider">
                @foreach($featured_deals->products as $key=>$product)
                @php($product=$product->product)
                <div class="d-flex  align-items-center justify-content-center"
                  style="height: 129px;border: 1px solid #c5bfbf54;border-radius: 5px; background: white">
                  <div class="featured_deal_product d-flex align-items-center justify-content-between">
                    <div class="row">
                      <div class="col-4">
                        <div class="featured_product_pic mt-3" style=" text-align: center;">
                          <a href="{{route('product',$product->slug)}}" class="image_center">
                            <img
                              src="{{\App\CPU\ProductManager::product_image_path('product')}}/{{json_decode($product['images'])[0]}}"
                              onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                              class="d-block w-100" alt="...">
                          </a>
                        </div>
                      </div>
                      <div class="col-8">
                        <div class="featured_product_details">
                          <h3 class="featured_product-title mt-2">
                            <a class="ptr" href="{{route('product',$product->slug)}}">
                              {{$product['name']}}
                            </a>
                          </h3>
                          <div class="featured_product-price">
                            <span class="text-accent ptp">
                              {{\App\CPU\Helpers::currency_converter(
                              $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))
                              )}}
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  @endif



  {{-- Categorized product --}}
  @foreach($home_categories as $category)
  @if(App\CPU\CategoryManager::products($category['id'])->count()>0)
  <section class="container rtl">
    <div class="section-header">
        <div class="feature_header d-flex align-items-center">
            <span class="for-feature-title capitalize">{{strtolower($category['name'])}}</span>
        </div>
        <div class="d-flex col-md-10 col-8 justify-content-between">
            <div class="float-right for-shoting-mobile d-flex">
                <form id="{{ $category['id'] }}-form" action="{{ route('products') }}" method="GET">
                    <div class="form-inline flex-nowrap for-mobile">
                        <label class="opacity-75 text-nowrap {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}} for-shoting"
                            for="sorting">
                            <span style="text-transform: lowercase;" class="{{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}">di</span>
                        </label>
                        {{ $category['name'] }}
                        <form action="{{ route('products') }}" method="get">
                            <input type="hidden" value="{{ $category['name'] }}" name="data_from">
                            <input type="hidden" value="catHome" name="type">
                            <input type="hidden" value="{{ $category['id'] }}" name="catId">
                            <input type="hidden" value="1" name="page">
                            @php($cate = $category['name'])
                        <select class="form-control custom-select capitalize" name="city" onchange="catSubmit({{ $category['id'] }})" style="border: none;
                        background: transparent;
                        color: red;
                        padding: 5px;
                        cursor: pointer;
                        text-overflow: ellipsis;
                        flex-wrap: nowrap;">
                            <option class="text-dark" value="">Semua lokasi</option>
                            @foreach ($city as $c)
                                <option class="text-dark capitalize" value="{{ $c['id'] }}"{{ request()->city == $c['id'] ? 'selected' : '' }}>{{ $c['name'] }}</option>
                            @endforeach
                        </select>
                        </form>
                    </div>
                </form>
            </div>
            <div class="d-none d-md-flex">
                <a class="btn btn-sm viw-btn-a"
                href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">
                {{ \App\CPU\translate('lihat_semua')}}
                </a>
            </div>
            <div class="d-flex d-md-none">
                <div class="dropleft d-flex align-items-center">
                    <a class="selectLoc" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                      Pilih lokasi
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">Semua</a>
                      @foreach ($city as $c)

                      <a class="dropdown-item" href="{{route('products',['city' => $c['id'], 'catId'=> $category['id'], 'data_from'=>$category['name'], 'type' => 'catHome','page'=>1])}}">{{ $c['name'] }}</a>
                      @endforeach
                    </div>
                  </div>
            </div>
        </div>
    </div>

    <div class="row mt-2 mb-3 w-100">
        @foreach(\App\CPU\CategoryManager::products($category['id']) as $key=>$product)
        @if($key<12)
        <div class="col-md-3 mb-3 col-sm-3 col-6 pl-0">
            @if (empty($country))
            @include('web-views.partials._single-product',['product'=>$product])
            @else
            @if($product['country'] == $country)
            {{-- {{ dd($product['country']) }} --}}
            @include('web-views.partials._single-product',['product'=>$product])
            @else
            <div id="empty" class="empty"></div>
            @endif
            @endif
        </div>
    @endif
    @endforeach
    </div>
  </section>
  @endif
  @endforeach

  <!--kampus-->
    <section class="container rtl">
        <!-- Heading-->
        <div class="section-header">
            <div class="feature_header">
                <span class="capitalize">{{ \App\CPU\translate('Kos_sekitar_kampus')}}</span>
            </div>
        </div>

        <div class="mt-2 mb-3 row">
                @foreach($ptn as $p)
                <div class="col-md-3 col-6 mb-2">
                    <div class="card px-2 p-2 py-md-4 kampus-card">
                        <a href="{{ route('products', ['data-from' => 'collage', 'collage_id' => $p->id ]) }}" class="row no-gutters">
                          <div class="col-md-4 pr-2 col-4 d-flex justify-content-center align-items-center">
                              <div class="img-frame">
                                  <img onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                  src="{{asset("storage/collage/$p->logo")}}" class="h-100"
                                  alt="{{$p->id}}">
                              </div>
                          </div>
                          @php($word = ['KOTA ', 'KABUPATEN'])
                          @php($rpl = ['', 'Kab.'])
                          {{-- {{ dd($p->city->name) }} --}}
                          <div class="col-md-8 col-8">
                              <div class="card-body p-0 kampus-body">
                                <h5 class="card-title my-1">{{ $p->short }}</h5>
                                @if (isset($p->city))
                                @php($city = str_replace($word, $rpl, $p->city->name))
                                <span class="card-text capitalize">{{ strtoLower($city) }}</span>
                                @endif
                            </div>
                          </div>
                        </a>
                      </div>
                </div>
                    {{-- <div class="kampus_div p-0" style="height: 132px; width: 100%;">
                        <a href="{{route('products',['id'=> $p['id'],'data_from'=>'category','page'=>1])}}">
                            <img style="vertical-align: middle; height: 88px"
                                onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                src="{{asset("storage/collage/$p->logo")}}"
                                alt="{{$p->name}}">
                                    <p class="text-center small mt-2"
                                        style="">{{Str::limit($p->short, 17)}}</p>
                        </a>
                    </div> --}}
                @endforeach
        </div>
    </section>
    <!--end kampus-->

<section class="article mt-5 d-none d-md-block"><div class="container-fluid row">
    <div class="article-footer">
        {!! $article['value'] !!}
    </div>
</section>
@endsection

  @push('script')
  {{-- Owl Carousel --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
    integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function flashSubmit(){
            $('#flash-form').submit();
        }

        function catSubmit(val){
            $('#'+val+'-form').submit()
        }
        function catSubmitMobile(val){
            $('#'+val+'-form-mobile').submit()
        }
    </script>

  <script>
    $('#flash-deal-slider').owlCarousel({
            loop: true,
            autoplay: false,
            margin: 20,
            nav: false,
            navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
            dots: true,
            autoplayHoverPause: true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 1
                },
                375: {
                    items: 1
                },
                540: {
                    items: 2
                },
                //Small
                576: {
                    items: 2
                },
                //Medium
                768: {
                    items: 3
                },
                //Large
                992: {
                    items: 4
                },
                //Extra large
                1200: {
                    items: 4
                },
                //Extra extra large
                1400: {
                    items: 4
                }
            }
        })

        $('#kampus-slider').owlCarousel({
            loop: true,
            autoplay: false,
            margin: 20,
            nav: false,
            // navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
            dots: true,
            autoplayHoverPause: true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 1
                },
                375: {
                    items: 1
                },
                540: {
                    items: 2
                },
                //Small
                576: {
                    items: 2
                },
                //Medium
                768: {
                    items: 2
                },
                //Large
                992: {
                    items: 4
                },
                //Extra large
                1200: {
                    items: 4
                },
                //Extra extra large
                1400: {
                    items: 4
                }
            }
        })

        $('#flash-deal-slider-mobile').owlCarousel({
            loop: true,
            autoplay: false,
            margin: 5,
            nav: true,
            navText: ["<i class='czi-arrow-left'></i>", "<i class='czi-arrow-right'></i>"],
            dots: false,
            autoplayHoverPause: false,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 2
                },
                375: {
                    items: 2.1
                },
                540: {
                    items: 2
                },
                //Small
                576: {
                    items: 2
                },
                //Medium
                768: {
                    items: 3
                },
                //Large
                992: {
                    items: 4
                },
                //Extra large
                1200: {
                    items: 4
                },
                //Extra extra large
                1400: {
                    items: 4
                }
            }
        })

        $('#web-feature-deal-slider').owlCarousel({
            loop: true,
            autoplay: true,
            margin: 5,
            nav: false,
            //navText: ["<i class='czi-arrow-left'></i>", "<i class='czi-arrow-right'></i>"],
            dots: true,
            autoplayHoverPause: true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 1
                },
                375: {
                    items: 1
                },
                540: {
                    items: 2
                },
                //Small
                576: {
                    items: 3
                },
                //Medium
                768: {
                    items: 3
                },
                //Large
                992: {
                    items: 3
                },
                //Extra large
                1200: {
                    items: 3
                },
                //Extra extra large
                1400: {
                    items: 3
                }
            }
        })

        $( window ).on( "load",function() {
            var work = $(".empty").parent('div').remove();
        });
  </script>

  <script>
    $('#brands-slider').owlCarousel({
            loop: false,
            autoplay: true,
            margin: 5,
            nav: false,
            //navText: ["<i class='czi-arrow-left'></i>","<i class='czi-arrow-right'></i>"],
            dots: true,
            autoplayHoverPause: true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 2
                },
                360: {
                    items: 3
                },
                375: {
                    items: 3
                },
                540: {
                    items: 4
                },
                //Small
                576: {
                    items: 5
                },
                //Medium
                768: {
                    items: 7
                },
                //Large
                992: {
                    items: 9
                },
                //Extra large
                1200: {
                    items: 11
                },
                //Extra extra large
                1400: {
                    items: 12
                }
            }
        })
  </script>

  <script>
    $('#category-slider, #top-seller-slider').owlCarousel({
            loop: false,
            autoplay: false,
            margin: 10,
            nav: false,
            // navText: ["<i class='czi-arrow-left'></i>","<i class='czi-arrow-right'></i>"],
            dots: false,
            autoplayHoverPause: true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 2
                },
                360: {
                    items: 3
                },
                375: {
                    items: 3
                },
                540: {
                    items: 4
                },
                //Small
                576: {
                    items: 5
                },
                //Medium
                768: {
                    items: 7
                },
                //Large
                992: {
                    items: 7
                },
                //Extra large
                1200: {
                    items: 7
                },
                //Extra extra large
                1400: {
                    items: 7
                }
            }
        })
  </script>

  <script>
    $('#banner-slider-custom').owlCarousel({
            loop: true,
            autoplay: true,
            margin: 15,
            nav: false,
            // navText: ["<i class='czi-arrow-left'></i>","<i class='czi-arrow-right'></i>"],
            dots: true,
            autoplayHoverPause: true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1.2
                },
                360: {
                    items: 1.2
                },
                375: {
                    items: 1.1
                },
                540: {
                    items: 1.6
                },
                //Small
                576: {
                    items: 1.6
                },
                //Medium
                768: {
                    items: 1.6
                },
                //Large
                992: {
                    items: 1.6
                },
                //Extra large
                1200: {
                    items: 1.3
                },
                //Extra extra large
                1400: {
                    items: 2
                }
            }
        })
  </script>
  @endpush

