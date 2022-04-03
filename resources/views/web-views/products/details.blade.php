@extends('layouts.front-end.app')

@section('title',$product['name'])

@push('css_or_js')
    <meta name="description" content="{{$product->slug}}">
    <meta name="keywords" content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
    @if($product->added_by=='seller')
        <meta name="author" content="{{ $product->seller->shop?$product->seller->shop->name:$product->seller->f_name}}">
    @elseif($product->added_by=='admin')
        <meta name="author" content="{{$web_config['name']->value}}">
    @endif
    <!-- Viewport-->

    @if($product['meta_image']!=null)
        <meta property="og:image" content="{{asset("storage/app/public/product/meta")}}/{{$product->meta_image}}"/>
        <meta property="twitter:card"
              content="{{asset("storage/app/public/product/meta")}}/{{$product->meta_image}}"/>
    @else
        <meta property="og:image" content="{{asset("storage/app/public/product/thumbnail")}}/{{$product->thumbnail}}"/>
        <meta property="twitter:card"
              content="{{asset("storage/app/public/product/thumbnail/")}}/{{$product->thumbnail}}"/>
    @endif

    @if($product['meta_title']!=null)
        <meta property="og:title" content="{{$product->meta_title}}"/>
        <meta property="twitter:title" content="{{$product->meta_title}}"/>
    @else
        <meta property="og:title" content="{{$product->name}}"/>
        <meta property="twitter:title" content="{{$product->name}}"/>
    @endif
    <meta property="og:url" content="{{route('product',[$product->slug])}}">

    @if($product['meta_description']!=null)
        <meta property="twitter:description" content="{!! $product['meta_description'] !!}">
        <meta property="og:description" content="{!! $product['meta_description'] !!}">
    @else
        <meta property="og:description"
              content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
        <meta property="twitter:description"
              content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
    @endif
    <meta property="twitter:url" content="{{route('product',[$product->slug])}}">

    <link rel="stylesheet" href="{{asset('public/assets/front-end/css/product-details.css')}}"/>
    <style>
        .thumblist-frame .cz-thumblist:first-child a {
            border-radius: 0 10px 0 0;
        }
        .thumblist-frame .cz-thumblist:last-child a {
            border-radius: 0 0 10px 0;
        }
        .carousel-item{
            height: 450px !important;
        }
        .msg-option {
            display: none;
        }

        .chatInputBox {
            width: 100%;
        }
        .product-img{
            /* height: 383px; */
        }
        .go-to-chatbox {
            width: 100%;
            text-align: center;
            padding: 5px 0px;
            display: none;
        }

        .feature_header {
            display: flex;
            justify-content: center;
        }

        .btn-number:hover {
            color: {{$web_config['secondary_color']}};

        }

        .for-total-price {
            margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: -30%;
        }

        .feature_header span {
            padding- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 15px;
            font-weight: 700;
            font-size: 25px;
            background-color: #ffffff;
            text-transform: uppercase;
        }
        button.carousel-control-next, button.carousel-control-prev {
            background-color: transparent;
            opacity: 1;
            border: none;
        }
        .carousel-control-prev i, .carousel-control-next i{
            color: #fff;
        }

        @media (max-width: 768px) {
            .card-body span {
                font-size: 14px;
            }
            .card-body img{
                height: 20px !important;
            }
            h6{
                font-size: 16px;
            }
            .card-header.section-head{
                padding-top: 5px;
            }
            h5.fasilitas{
                font-size: 18px;
                margin-bottom: 5px;
            }
            .detail-kost-additional-widget{
                flex-direction: column;
                margin-top: 37px;
                align-items: flex-start;
            }
            .detail-kost-additional-widget__left-section{
                margin-bottom: 18px;
            }
            .detail-kost-overview{
                flex-direction: column;
                align-items: flex-start;
            }
            .detail-kost-overview .detail-kost-overview__gender{
                font-size: 14px;
            }
            .detail-kost-overview__area{
                margin-top: 10px;
            }
            .detail-kost-overview .detail-kost-overview__area .detail-kost-overview__area-text{
                font-size: 14px;
            }
            .product-footer{
                position: fixed;
                bottom: 0;
                left: 0;
                padding: 10px;
                background-color: #fff;
                right: 0;
                z-index: 20;
            }
            .price-foot {
                font-size: 14px;
                font-weight: 600;
            }
            .price-foot .month{
                color: #6f6f6f;
            }
            .details h1.h3{
                font-size: 22px;
                font-weight: 600 !important;
            }
            .mobile-margin{
                margin-top: -10px;
            }
            .cz-preview{
                z-index: 1 !important;
                border-radius: 8px;
            }
            .feature_header span {
                margin-bottom: -40px;
            }

            .for-total-price {
                padding- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 30%;
            }

            .product-quantity {
                padding- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 4%;
            }

            .for-margin-bnt-mobile {
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 7px;
            }

            .font-for-tab {
                font-size: 11px !important;
            }

            .pro {
                font-size: 13px;
            }
        }

        @media (max-width: 375px) {
            .for-margin-bnt-mobile {
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 3px;
            }

            .for-discount {
                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10% !important;
            }

            .for-dicount-div {
                margin-top: -5%;
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: -7%;
            }

            .product-quantity {
                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 4%;
            }

        }

        @media (max-width: 500px) {
            .carousel-item{
            height: 250px !important;
            }
            /* .product-img{
                width: 100%;
            } */
            .modal-dialog{
                top:7%;
            }
            h5.modal-title{
                font-size: 16px;
                text-transform: capitalize;
            }
            .specification p{
                font-size: 14px;
            }
            .owner-feedback .owner-feedback__title, .owner-feedback .owner-feedback__description{
                font-size: 14px;
            }
            .owner-feedback .owner-feedback__date{
                font-size: 12px;
            }
            .seller_details{
                height: 90px;
            }
            .seller_shop {
                display: flex !important;
                justify-content: space-between !important;
            }
            .for-dicount-div {
                margin-top: -4%;
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: -5%;
            }

            .for-total-price {
                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: -20%;
            }

            .view-btn-div {

                margin-top: -9%;
                float: {{Session::get('direction') === "rtl" ? 'left' : 'right'}};
            }

            .for-discount {
                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 7%;
            }

            .viw-btn-a {
                font-size: 10px;
                font-weight: 600;
            }

            .feature_header span {
                margin-bottom: -7px;
            }

            .for-mobile-capacity {
                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 7%;
            }
        }
    </style>
    <style>
        thead {
            background: {{$web_config['primary_color']}}!important;
            color: white;
        }
        th, td {
            border-bottom: 1px solid #ddd;
            padding: 5px;
        }


    </style>
@endpush

@section('content')
    <?php
    $overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews);
    $rating = \App\CPU\ProductManager::get_rating($product->reviews);
    ?>
    <!-- Page Content-->
    <div class="mobile-margin d-block d-md-none"></div>
    <div class="container mt-4 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <!-- General info tab-->
        <div class="row" style="direction: ltr">
            <!-- Product gallery-->

            <div class="col-lg-7 col-md-7 col-12">
                <div class="cz-product-gallery">
                    <div class="cz-preview" id="cz-preview">
                        <div id="carouselExampleControls" data-interval="false" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                            @if($product->images!=null)
                            @foreach ($img as $key => $photo)
                                <div
                                    class="carousel-item h-100 {{$key==0?'active':''}}"
                                    id="image{{$key}}">
                                    <img class="w-100 product-img"
                                        onerror="this.src='{{asset('storage/kost').'/'.$photo}}'"
                                        src="{{asset("storage/product/$photo")}}"
                                        alt="Product image" width="">
                                </div>
                            @endforeach
                            @endif
                            </div>
                            <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
                                <i class="fa fa-chevron-left"></i>
                            </button>
                            <button class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
                                <i class="fa fa-chevron-right"></i>
                            </button>
                        </div>

                    </div>
                </div>
                {{-- {{ dd($product) }} --}}
                <div class="details mt-md-4 mt-2">
                    @php($ganti = ['KABUPATEN', 'KOTA '])
                    @php($dg = ['Kab.', ''])
                    @php($filter = str_replace($ganti, $dg, $product->kost->city))
                    <h1 class="h3 mb-2 capitalize">{{$product->kost->name}} {{ strToLower($filter) }}</h1>
                    <div class="d-flex align-items-center mb-2">
                        <section class="detail-kost-overview" style="height: 40px;">
                            <div class="detail-kost-overview__left-section">
                                <span class="detail-kost-overview__gender capitalize">
                                    {{ $product->kost->penghuni }}
                                </span>
                                <span class="detail-kost-overview__divider">·</span>
                                @for($inc=0;$inc<1;$inc++)
                                @if($inc<$overallRating[0])
                                <div class="detail-kost-overview__rating">
                                        <i class="sr-star czi-star-filled active"></i>
                                        <span class="detail-kost-overview__rating-text">{{$overallRating[1]}}</span>
                                        {{-- <span class="detail-kost-overview__rating-review">
                                            <span class="font-for-tab d-inline-block font-size-sm text-body align-middle">({{$overallRating[1]}})</span>
                                        </span> --}}
                                </div>
                                <span class="detail-kost-overview__divider d-none d-md-flex">·</span>
                                @endif
                                @endfor
                            </div>
                            <div class="detail-kost-overview__right-section">
                                <div class="detail-kost-overview__area pl-1">
                                    <i class="detail-kost-overview__area-icon bg-c-icon bg-c-icon--sm fa fa-map-marker">
                                    </i>
                                    <span class="detail-kost-overview__area-text capitalize">Kec. {{ strToLower($product->kost->district) }}</span>
                                </div>
                            </div>
                        </section>
                    </div>
                    <section class="detail-kost-additional-widget">
                        <div class="detail-kost-additional-widget__left-section">
                            <div class="detail-kost-overview__availability">
                                <div class="detail-kost-overview__availability-icon">
                                    <img src="{{ asset('assets/front-end/img/doors.png') }}" class="bg-c-icon bg-c-icon--md" alt="others" style="height: 15px">
                                    </img>
                                </div>
                                <div class="detail-kost-overview__availability-wrapper">
                                    <span >Banyak pilihan kamar untukmu</span>
                                </div>
                            </div>
                        </div>
                        <div id="detailKostOverviewFavShare" class="detail-kost-overview-widget">
                            <div class="detail-kost-overview-widget__favorite-button" onclick="addWishlist('{{$product['id']}}')">
                                <button type="button" class="bg-c-button detail-kost-additional-widget__outer bg-c-button--tertiary bg-c-button--md">
                                    <i role="img" class="bg-c-button__icon bg-c-icon bg-c-icon--sm fa fa-heart-o" style="margin-right: 7px; margin-left: 0px;">
                                    </i>
                                    Simpan
                                </button>
                            </div>
                            <div class="bg-c-dropdown dropdown">
                                {{-- <div role="button" class="bg-c-dropdown__trigger"> --}}
                                    <button type="button"
                                    id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false"
                                    class="dropdown-toggle bg-c-button detail-kost-additional-widget__outer --not-first bg-c-button--tertiary bg-c-button--md">
                                        <i role="img" class="bg-c-button__icon bg-c-icon bg-c-icon--sm fa fa-share-alt" style="margin-right: 7px; margin-left: 0px;">
                                        </i>
                                        Bagikan
                                    </button>
                                {{-- </div> --}}
                                <div style="left: -60px;" class="dropdown-menu bg-c-dropdown__menu dropdown-share bg-c-dropdown__menu--fit-to-content bg-c-dropdown__menu--text-lg" aria-labelledby="dropdownMenuButton">
                                    <div style="text-align:center;"
                                    class="sharethis-inline-share-buttons">
                                        <div class="st-btn st-first st-remove-label" data-network="facebook" style="display: block;">
                                            <img alt="facebook sharing button" src="https://platform-cdn.sharethis.com/img/facebook.svg">
                                            <span class="st-label">Share</span>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!--
                    <div class="mb-3">
                        <span
                            class="h3 font-weight-normal text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}">
                            {{\App\CPU\Helpers::get_price_range($product) }}
                        </span>
                        @if($product->discount > 0)
                            <strike style="color: {{$web_config['secondary_color']}};">
                                {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                            </strike>
                        @endif
                    </div>

                    @if($product->discount > 0)
                        <div class="mb-3">
                            <strong>{{\App\CPU\translate('discount')}} : </strong>
                            <strong id="set-discount-amount"></strong>
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong>{{\App\CPU\translate('tax')}} : </strong>
                        <strong id="set-tax-amount"></strong>
                    </div>
                        <div class="row no-gutters">
                            <div class="col-2">
                                <div class="product-description-label mt-2">{{\App\CPU\translate('Quantity')}}:</div>
                            </div>
                            <div class="col-10">
                                <div class="product-quantity d-flex align-items-center">
                                    <div
                                        class="input-group input-group--style-2 {{Session::get('direction') === "rtl" ? 'pl-3' : 'pr-3'}}"
                                        style="width: 160px;">
                                        <span class="input-group-btn">
                                            <button class="btn btn-number" type="button"
                                                    data-type="minus" data-field="quantity"
                                                    disabled="disabled" style="padding: 10px">
                                                -
                                            </button>
                                        </span>
                                        <input type="text" name="quantity"
                                               class="form-control input-number text-center cart-qty-field"
                                               placeholder="1" value="1" min="1" max="100">
                                        <span class="input-group-btn">
                                            <button class="btn btn-number" type="button" data-type="plus"
                                                    data-field="quantity" style="padding: 10px">
                                               +
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row flex-start no-gutters d-none mt-2" id="chosen_price_div">
                            <div class="{{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">
                                <div class="product-description-label">{{\App\CPU\translate('total_price')}}:</div>
                            </div>
                            <div>
                                <div class="product-price for-total-price">
                                    <strong id="chosen_price"></strong>
                                </div>
                            </div>

                            <div class="col-12">
                                @if($product['current_stock']<=0)
                                    <h5 class="mt-3" style="color: red">{{\App\CPU\translate('out_of_stock')}}</h5>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <button
                                class="btn btn-secondary element-center btn-gap-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}"
                                onclick="buy_now()"
                                type="button"
                                style="width:37%; height: 45px">
                                <span class="string-limit">{{\App\CPU\translate('buy_now')}}</span>
                            </button>
                            <button
                                class="btn btn-primary element-center btn-gap-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}"
                                onclick="addToCart()"
                                type="button"
                                style="width:37%; height: 45px">
                                <span class="string-limit">{{\App\CPU\translate('add_to_cart')}}</span>
                            </button>
                            <button type="button" onclick="addWishlist('{{$product['id']}}')"
                                    class="btn btn-dark for-hover-bg"
                                    style="">
                                <i class="fa fa-heart-o {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"
                                   aria-hidden="true"></i>
                                <span class="countWishlist-{{$product['id']}}">{{$countWishlist}}</span>
                            </button>
                        </div>
                    </form> -->
                    <hr class="my-4" style="padding-bottom: 10px">

                    <!-- fasilitas -->
                    <div class="container">
                        <div class="section-header">
                            <h5 class="fasilitas">{{ App\CPU\translate('Fasilitas') }}</h5>
                        </div>
                        <div class="card-header pb-1 section-head">
                            <h6 class="mb-1">{{ App\CPU\translate('room_size') }}</h6>
                        </div>
                        <div class="card-body pt-0 body-detail-product d-flex">
                            <img class="mr-3" src="{{ asset('assets/front-end/img/room.png') }}" alt="room" style="height: 23px">
                            <span>
                                {{ $product->size }}
                            </span>
                        </div>

                        <div class="card-header pb-1 section-head">
                            <h6 class="mb-1">{{ App\CPU\translate('room_facility') }}</h6>
                        </div>
                        <div class="card-body pt-0 body-detail-product">
                            @foreach (json_decode($product->fasilitas_id) as $f)
                            @php($fas = App\CPU\Helpers::fasilitas($f))
                            <div class="item-facility d-flex mb-2">
                                <img onerror="this.src='{{asset('assets/front-end/img/bantal.png')}}'" class="mr-3" src="{{ asset('assets/front-end/img').'/'.strtolower($fas).'.png' }}" alt="broken" style="height: 23px">
                                <span>
                                    {{ $fas }}
                                </span>
                            </div>
                            @endforeach
                        </div>

                        <div class="card-header pb-1 section-head">
                            <h6 class="mb-1">{{ App\CPU\translate('general_facility') }}</h6>
                        </div>
                        <div class="card-body pt-0 body-detail-product">
                            @foreach (json_decode($product->kost->fasilitas_id) as $f)
                            @php($fas = App\CPU\Helpers::fasilitas($f))
                            <div class="item-facility d-flex mb-2">
                                <img onerror="this.src='{{asset('assets/front-end/img/tv.png')}}'" class="mr-3" src="{{ asset('assets/front-end/img').'/'.strtolower($fas).'.png' }}" alt="broken" style="height: 23px">
                                <span>
                                    {{ $fas }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- end fasilitas -->
                    <hr class="my-4" style="padding-bottom: 10px">

                    <!-- lokasi -->
                    <div class="container">
                        <div class="section-header">
                            <h5 class="fasilitas">{{ App\CPU\translate('Lokasi') }}</h5>
                        </div>
                        <div class="card-header pb-1 section-head d-flex">
                            <i class="fa fa-map-marker mr-2" style="font-size: 23px"></i>
                            <span class="capitalize">
                                {{ 'Kec. '.strToLower($product->kost->district).', '.strToLower($filter).', '.strtolower($product->kost->province) }}
                            </span>
                        </div>
                        <div class="card-body mt-2 p-3 body-detail-product d-flex ml-4 capitalize" style="border: 1px solid #d5d5d5; border-radius: 5px;">
                            {!! $product->kost->note_address !!}
                        </div>
                    </div>
                    <!-- end fasilitas -->
                    <hr class="my-4" style="padding-bottom: 10px">

                    <!-- seller section -->
                    @if($product->added_by=='seller')
                        <div class="container mt-4 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            <div class="row seller_details d-flex align-items-center" id="sellerOption">
                                <div class="col-md-6">
                                    <div class="seller_shop">
                                        <div class="shop_image d-flex justify-content-center align-items-center">
                                            <a href="#" class="d-flex justify-content-center">
                                                <img style="height: 65px; width: 65px; border-radius: 50%"
                                                    src="{{asset('storage/kost/')}}/{{json_decode($product->kost->images)->depan}}"
                                                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                    alt="">
                                            </a>
                                        </div>
                                        <div
                                            class="shop-name-{{Session::get('direction') === "rtl" ? 'right' : 'left'}} d-flex justify-content-center align-items-center">
                                            <div>
                                                <a href="#" class="d-flex align-items-center">
                                                    <div
                                                        class="title">{{$product->kost->name}}</div>
                                                </a>
                                                <div class="review d-flex align-items-center">
                                                    <div class="">
                                                        <span
                                                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">{{\App\CPU\translate('Seller')}} {{\App\CPU\translate('Info')}} </span>
                                                        <span
                                                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"></span>
                                                    </div>
                                                </div>
                                                <div class="review d-flex align-items-center">
                                        </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-md-6 p-md-0 pt-sm-3">
                                    <div class="seller_contact">
                                        <div
                                            class="d-flex align-items-center {{Session::get('direction') === "rtl" ? 'pl-4' : 'pr-4'}}">
                                            <a href="{{ route('shopView',[$product->seller->id]) }}">
                                                <button class="btn btn-secondary">
                                                    <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                                    {{\App\CPU\translate('Visit')}}
                                                </button>
                                            </a>
                                        </div>

                                        @if (auth('customer')->id() == '')
                                            <div class="d-flex align-items-center">
                                                <a href="{{route('customer.auth.login')}}">
                                                    <button class="btn btn-primary">
                                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                                        {{\App\CPU\translate('Contact')}} {{\App\CPU\translate('Seller')}}
                                                    </button>
                                                </a>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center" id="contact-seller">
                                                <button class="btn btn-primary">
                                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                                    {{\App\CPU\translate('Contact')}} {{\App\CPU\translate('Seller')}}
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div> --}}
                            </div>
                            <div class="row msg-option" id="msg-option">
                                <form action="">
                                    <input type="text" class="seller_id" hidden seller-id="{{$product->seller->id }}">
                                    <textarea shop-id="{{$product->seller->shop->id}}" class="chatInputBox"
                                            id="chatInputBox" rows="5"> </textarea>
                                    <button class="btn btn-secondary" style="color: white;"
                                            id="cancelBtn">{{\App\CPU\translate('cancel')}}
                                    </button>
                                    <button class="btn btn-primary" style="color: white;"
                                            id="sendBtn">{{\App\CPU\translate('send')}}</button>
                                </form>
                            </div>
                            <div class="go-to-chatbox" id="go_to_chatbox">
                                <a href="{{route('chat-with-seller')}}" class="btn btn-primary" id="go_to_chatbox_btn">
                                    {{\App\CPU\translate('go_to')}} {{\App\CPU\translate('chatbox')}} </a>
                            </div>
                        </div>
                    @else
                        <div class="container rtl mt-3" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            <div class="row seller_details d-flex align-items-center" id="sellerOption">
                                <div class="col-md-6">
                                    <div class="seller_shop">
                                        <div class="shop_image d-flex justify-content-center align-items-center">
                                            <a href="{{ route('shopView',[0]) }}" class="d-flex justify-content-center">
                                                <img style="height: 65px;width: 65px; border-radius: 50%"
                                                    src="{{asset("storage/company")}}/{{$web_config['fav_icon']->value}}"
                                                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                    alt="">
                                            </a>
                                        </div>
                                        <div
                                            class="shop-name-{{Session::get('direction') === "rtl" ? 'right' : 'left'}} d-flex justify-content-center align-items-center">
                                            <div>
                                                <a href="#" class="d-flex align-items-center">
                                                    <div
                                                        class="title">{{$web_config['name']->value}}</div>
                                                </a>
                                                <div class="review d-flex align-items-center">
                                                    <div class="">
                                                        <span
                                                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">{{ \App\CPU\translate('web_admin')}}</span>
                                                        <span
                                                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"></span>
                                                    </div>
                                                </div>
                                                <div class="review d-flex align-items-center">
                                            {{-- <div class="w-100 d-flex">
                                                <div class="flag">
                                                    <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}" width="20"
                                                        src="{{asset('public/assets/front-end')}}/img/flags/id.png" alt="Eng"
                                                        style="width: 20px">
                                                </div>
                                                <span
                                                    class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "
                                                    rtl" ? 'ml-2' : 'mr-2' }}" style="line-height: 1.2;">Indonesia </span>
                                                <span
                                                    class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "
                                                    rtl" ? 'mr-2' : 'ml-2' }}"></span>
                                            </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6 p-md-0 pt-sm-3">
                                    <div class="seller_contact">
                                        <div
                                            class="d-flex align-items-center {{Session::get('direction') === "rtl" ? 'pl-4' : 'pr-4'}}">
                                            <a href="{{ route('shopView',[0]) }}">
                                                <button class="btn btn-secondary">
                                                    <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                                    {{\App\CPU\translate('Visit')}}
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    @endif
                    <!-- end seller section -->

                    <hr class="my-4" style="padding-bottom: 10px">

                    <!-- atiuran kos -->
                    <div class="container mt-4">
                        <div class="section-header">
                            <h5>
                                {{ App\CPU\translate('rule') }}
                            </h5>
                        </div>
                        <div class="card-body pt-1">
                            @foreach (json_decode($product->kost->aturan_id) as $a)
                            <div class="item-facility">
                                <img onerror="this.src='{{asset('assets/front-end/img/rules.png')}}'" class="mr-3" src="{{ asset('assets/front-end/img').'/'.strtolower($a).'.png' }}" alt="broken" style="height: 23px">
                                <span>{{ App\CPU\helpers::aturan($a) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- end atiuran kos -->

                    @if ($product->kost->note)
                    <hr class="my-4" style="padding-bottom: 10px">

                    <!-- laiinya kos -->

                    <div class="container">
                        <div class="section-header">
                            <h5 class="">
                                {{ App\CPU\translate('note') }}
                            </h5>
                        </div>
                        <div class="row pt-2 specification">
                            <div class="col-lg-12 col-md-12 pl-4">
                                {!! $product->kost['note'] !!}
                            </div>
                        </div>
                    </div>
                    <!-- end laiinya kos -->
                    @endif
                    <hr class="my-4" style="padding-bottom: 10px">
                    <div class="container">
                        <div class="section-header">
                            <h5 class="">
                                {{ App\CPU\translate('additional_info') }}
                            </h5>
                        </div>
                        <div class="row pt-2 specification">
                            <div class="col-lg-12 col-md-12 pl-4">
                                {!! $product->kost['deskripsi'] !!}
                            </div>
                        </div>
                    </div>
                    <!-- overview section -->
                    <div class="kost-review container">
                        <div class="kost-review__divider">
                            <span role="separator" class="bg-c-divider"></span>
                        </div> <div class="kost-review__content">
                            <div class="kost-review__overview">
                                <i class="fa fa-star bg-c-icon" style="font-size: 20px"></i>
                                <span class="kost-review__overview-rating">5.0 (1 review)</span>
                            </div>
                            <div class="kost-review-fac-rating">
                                <div class="col-12 text-center pt-sm-3 pt-md-0">
                                    <div class="d-flex align-items-center mb-2">
                                        <div
                                            class="text-nowrap {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}"><span
                                                class="d-inline-block align-middle text-muted">{{\App\CPU\translate('5')}}</span><i
                                                class="czi-star-filled font-size-xs {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}"></i>
                                        </div>
                                        <div class="w-100">
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: <?php echo $widthRating = ($rating[0] != 0) ? ($rating[0] / $overallRating[1]) * 100 : (0); ?>%;"
                                                    aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <span
                                            class="text-muted {{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">
                                    {{$rating[0]}}
                                </span>
                                    </div>

                                    <div class="d-flex align-items-center mb-2">
                                        <div
                                            class="text-nowrap {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}"><span
                                                class="d-inline-block align-middle text-muted">{{\App\CPU\translate('4')}}</span><i
                                                class="czi-star-filled font-size-xs {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}"></i>
                                        </div>
                                        <div class="w-100">
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: <?php echo $widthRating = ($rating[1] != 0) ? ($rating[1] / $overallRating[1]) * 100 : (0); ?>%; background-color: #a7e453;"
                                                    aria-valuenow="27" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <span
                                            class="text-muted {{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">
                                {{$rating[1]}}
                                </span>
                                    </div>

                                    <div class="d-flex align-items-center mb-2">
                                        <div
                                            class="text-nowrap {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}"><span
                                                class="d-inline-block align-middle text-muted">{{\App\CPU\translate('3')}}</span><i
                                                class="czi-star-filled font-size-xs ml-1"></i></div>
                                        <div class="w-100">
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: <?php echo $widthRating = ($rating[2] != 0) ? ($rating[2] / $overallRating[1]) * 100 : (0); ?>%; background-color: #ffda75;"
                                                    aria-valuenow="17" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <span
                                            class="text-muted {{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">
                                    {{$rating[2]}}
                                </span>
                                    </div>

                                    <div class="d-flex align-items-center mb-2">
                                        <div
                                            class="text-nowrap {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}"><span
                                                class="d-inline-block align-middle text-muted">{{\App\CPU\translate('2')}}</span><i
                                                class="czi-star-filled font-size-xs {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}"></i>
                                        </div>
                                        <div class="w-100">
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: <?php echo $widthRating = ($rating[3] != 0) ? ($rating[3] / $overallRating[1]) * 100 : (0); ?>%; background-color: #fea569;"
                                                    aria-valuenow="9" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <span
                                            class="text-muted {{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">
                                {{$rating[3]}}
                                </span>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="text-nowrap {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}"><span
                                                class="d-inline-block align-middle text-muted">{{\App\CPU\translate('1')}}</span><i
                                                class="czi-star-filled font-size-xs {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}"></i>
                                        </div>
                                        <div class="w-100">
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar bg-danger" role="progressbar"
                                                    style="width: <?php echo $widthRating = ($rating[4] != 0) ? ($rating[4] / $overallRating[1]) * 100 : (0); ?>%;"
                                                    aria-valuenow="4" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <span
                                            class="text-muted {{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">
                                {{$rating[4]}}
                                </span>
                                    </div>
                                </div>
                            </div>
                            <div class="kost-review__users-feedback"><div class="users-feedback-container users-feedback-container--card"><div class="users-feedback"><div class="users-feedback__section"><div class="user-feedback__header"><img alt="foto profile" class="user-feedback__photo" data-src="null" src="{{ asset('assets/front-end/img/user.png') }}" lazy="error"> <div class="user-feedback__profile">
                    <p class="user-feedback__profile-name bg-c-text bg-c-text--body-1 ">Agita Essa Putri</p>
                    <p class="bg-c-text bg-c-text--label-2 ">1 bulan yang lalu</p>
                </div>
                <div class="p-2 user-feedback__rating bg-c-label bg-c-label--rainbow bg-c-label--rainbow-white"
                ><i class="user-feedback__rating-star bg-c-icon bg-c-icon--sm fa fa-star">
                    <title>star-glyph</title> <use href="#basic-star-glyph"></use></i> <p class="bg-c-text bg-c-text--body-1 ">5.0</p></div></div> <div class="user-feedback__body"><div data-v-2fd2a78f=""><p class="user-feedback__content-text bg-c-text bg-c-text--body-4 ">Cukup nyaman dan sesuai harga, pelayanan sangat bagus..</p></div> <div data-v-8bbcb614="" class="owner-feedback"><span data-v-8bbcb614="" class="owner-feedback__title">Balasan dari Pemilik kos</span> <span data-v-8bbcb614="" class="owner-feedback__date">1 bulan yang lalu</span> <p data-v-8bbcb614="" class="owner-feedback__description">
            Hi kak, terimakasih banyak atas ulasan dan bintangnya, senang mendengar kakak nyaman singgah di sini :)

        </p></div></div></div></div></div></div> <!--fragment#127eff51545#head--><div role="dialog" class="modal fade" fragment="127eff51545" id="modalAllReview"><div role="document" class="modal-dialog"><div class="modal-content"><div class="kost-review-modal-header"><span class="kost-review-modal-header__close"><svg role="img" class="bg-c-icon bg-c-icon--md"><title>close</title> <use href="#basic-close"></use></svg></span></div> <div class="kost-review-modal-content"><div class="kost-review-modal-content__header"><svg role="img" class="bg-c-icon bg-c-icon--md"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg> <span class="kost-review-modal-content__title">5.0 (1 review)</span></div> <div class="kost-review-fac-rating"><div class="kost-review-fac-rating__column"><div class="kost-review-fac-rating__item"><span class="kost-review-fac-rating__title">
                    Kebersihan
                </span> <div class="kost-review-fac-rating__value"><div class="star-container"><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span></div> <span class="kost-review-fac-rating__value-text">5.0</span></div></div><div class="kost-review-fac-rating__item"><span class="kost-review-fac-rating__title">
                    Kenyamanan
                </span> <div class="kost-review-fac-rating__value"><div class="star-container"><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span></div> <span class="kost-review-fac-rating__value-text">5.0</span></div></div><div class="kost-review-fac-rating__item"><span class="kost-review-fac-rating__title">
                    Keamanan
                </span> <div class="kost-review-fac-rating__value"><div class="star-container"><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span></div> <span class="kost-review-fac-rating__value-text">5.0</span></div></div></div><div class="kost-review-fac-rating__column"><div class="kost-review-fac-rating__item"><span class="kost-review-fac-rating__title">
                    Harga
                </span> <div class="kost-review-fac-rating__value"><div class="star-container"><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span></div> <span class="kost-review-fac-rating__value-text">5.0</span></div></div><div class="kost-review-fac-rating__item"><span class="kost-review-fac-rating__title">
                    Fasilitas Kamar
                </span> <div class="kost-review-fac-rating__value"><div class="star-container"><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span></div> <span class="kost-review-fac-rating__value-text">5.0</span></div></div><div class="kost-review-fac-rating__item"><span class="kost-review-fac-rating__title">
                    Fasilitas Umum
                </span> <div class="kost-review-fac-rating__value"><div class="star-container"><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span><span class="star fa fa-star" style="color: rgb(64, 64, 64); margin-left: 2px;"><svg role="img" class="bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg></span></div> <span class="kost-review-fac-rating__value-text">5.0</span></div></div></div></div> <div class="kost-review-modal-content__sorting"><div data-v-aa3ef0a4="" id="baseMainFilter"><div data-v-aa3ef0a4="" class="bg-c-dropdown"><div role="button" class="bg-c-dropdown__trigger"><span data-v-aa3ef0a4="" role="text" class="bg-c-tag bg-c-tag--md" data-testid="filter-tag"><svg role="img" class="bg-c-tag__left-content bg-c-icon bg-c-icon--sm"><title>sorting</title> <use href="#basic-sorting"></use></svg>
                    Review terbaru
                </span></div> <div class="bg-c-dropdown__menu bg-c-dropdown__menu--fit-to-content bg-c-dropdown__menu--text-lg"><ul> <li data-v-aa3ef0a4="" style="width: 220px;"><span class=""><!----> <div data-v-aa3ef0a4="" class="dropdown-menu__content dropdown-menu__content--full"><span data-v-aa3ef0a4="" class="dropdown-menu__content-filter-title"></span> <div data-v-aa3ef0a4="" class="dropdown-menu__content-filter-data"><!--fragment#f0f2c72ded#head--><div data-v-6a58aa2f="" fragment="f0f2c72ded" class="filter-input"><label data-v-6a58aa2f="" class="filter-input__label"><input data-v-6a58aa2f="" type="radio" value="new"> <span data-v-6a58aa2f="" class="filter-input__label--active">Review terbaru</span></label></div><div data-v-6a58aa2f="" fragment="f0f2c72ded" class="filter-input"><label data-v-6a58aa2f="" class="filter-input__label"><input data-v-6a58aa2f="" type="radio" value="last"> <span data-v-6a58aa2f="" class="filter-input__label--active">Review terlama</span></label></div><div data-v-6a58aa2f="" fragment="f0f2c72ded" class="filter-input"><label data-v-6a58aa2f="" class="filter-input__label"><input data-v-6a58aa2f="" type="radio" value="best"> <span data-v-6a58aa2f="" class="filter-input__label--active">Rating tertinggi</span></label></div><div data-v-6a58aa2f="" fragment="f0f2c72ded" class="filter-input"><label data-v-6a58aa2f="" class="filter-input__label"><input data-v-6a58aa2f="" type="radio" value="bad"> <span data-v-6a58aa2f="" class="filter-input__label--active">Rating terendah</span></label></div><!--fragment#f0f2c72ded#tail--></div></div></span></li></ul></div></div></div></div> <div class="kost-review-modal-content__users-feedback"><div class="users-feedback-container" review-modal-scroll-position="[object Object]"><div class="users-feedback"><div class="users-feedback__section"><div class="user-feedback__header"><img alt="foto profile" class="user-feedback__photo" data-src="null" src="/general/img/pictures/navbar/ic_profile.svg" lazy="loading"> <div class="user-feedback__profile"><p class="user-feedback__profile-name bg-c-text bg-c-text--body-1 ">Agita Essa Putri</p> <p class="bg-c-text bg-c-text--label-2 ">1 bulan yang lalu</p></div> <div class="user-feedback__rating bg-c-label bg-c-label--rainbow bg-c-label--rainbow-white"><svg role="img" class="user-feedback__rating-star bg-c-icon bg-c-icon--sm"><title>star-glyph</title> <use href="#basic-star-glyph"></use></svg> <p class="bg-c-text bg-c-text--body-1 ">5.0</p></div></div> <div class="user-feedback__body"><div data-v-2fd2a78f=""><p class="user-feedback__content-text bg-c-text bg-c-text--body-4 ">Cukup nyaman dan sesuai harga, pelayanan sangat bagus..</p></div> <div data-v-8bbcb614="" class="owner-feedback"><span data-v-8bbcb614="" class="owner-feedback__title">Balasan dari Pemilik kos</span> <span data-v-8bbcb614="" class="owner-feedback__date">1 bulan yang lalu</span> <p data-v-8bbcb614="" class="owner-feedback__description">
            Hi kak, terimakasih banyak atas ulasan dan bintangnya, senang mendengar kakak nyaman singgah di sini :)

        </p></div></div></div></div></div></div> <div class="kost-review-modal-content__loading"><button type="button" class="bg-c-button bg-c-button--primary-naked bg-c-button--md bg-c-button--block"></button></div></div> <div class="modal-footer"><button type="button" class="btn btn-default">Close</button> <button type="button" class="btn btn-primary">Save changes</button></div></div></div></div> <div data-v-653cdb21="" fragment="127eff51545"><div data-v-653cdb21="" tabindex="-1" role="dialog" class="bg-c-modal bg-c-modal--backdrop bg-c-modal--button-block bg-c-modal--md bg-c-modal--popup"><!----></div></div><!--fragment#127eff51545#tail--> <div data-v-7e062822="" role="dialog" class="modal fade" id="modalDetailKostSwiperGallery"><div role="document" class="modal-dialog"><div class="modal-content"><div data-v-7e062822="" class="kost-gallery-modal-header"><span data-v-7e062822="" class="kost-gallery-modal-header__close"><svg data-v-7e062822="" role="img" class="bg-c-icon bg-c-icon--md"><title>close</title> <use href="#basic-close"></use></svg></span></div> <div data-v-7e062822="" class="kost-gallery-modal-content"><!----></div> <div class="modal-footer"><button type="button" class="btn btn-default">Close</button> <button type="button" class="btn btn-primary">Save changes</button></div></div></div></div></div></div>


                    <!-- <div class="container mt-4 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <div class="row" style="background: white">
                            <div class="col-12">
                                <div class="product_overview mt-1">
                                    <ul class="nav nav-tabs d-flex justify-content-center" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#overview" data-toggle="tab" role="tab"
                                            style="color: black !important;">
                                                {{\App\CPU\translate('Info_tambahan')}}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#reviews" data-toggle="tab" role="tab"
                                            style="color: black !important;">
                                                {{\App\CPU\translate('Reviews')}}
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="px-4 pt-lg-3 pb-3 mb-3">
                                        <div class="tab-content px-lg-3">
                                            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                                                <div class="row pt-2 specification">
                                                    {{-- @if($product->video_url!=null)
                                                        <div class="col-12 mb-4">
                                                            <iframe width="420" height="315"
                                                                    src="{{$product->video_url}}">
                                                            </iframe>
                                                        </div>
                                                    @endif --}}
                                                    <div class="col-lg-12 col-md-12">
                                                        {!! $product->kost['deskripsi'] !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="reviews" role="tabpanel">
                                                <div class="row pt-2 pb-3">
                                                    <div class="col-lg-4 col-md-5 ">
                                                        <h2 class="overall_review mb-2">{{$overallRating[1]}}
                                                            &nbsp{{\App\CPU\translate('Reviews')}} </h2>
                                                        <div
                                                            class="star-rating {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">
                                                            @if (round($overallRating[0])==5)
                                                                @for ($i = 0; $i < 5; $i++)
                                                                    <i class="czi-star-filled font-size-sm text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                @endfor
                                                            @endif
                                                            @if (round($overallRating[0])==4)
                                                                @for ($i = 0; $i < 4; $i++)
                                                                    <i class="czi-star-filled font-size-sm text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                @endfor
                                                                <i class="czi-star font-size-sm text-muted {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                            @endif
                                                            @if (round($overallRating[0])==3)
                                                                @for ($i = 0; $i < 3; $i++)
                                                                    <i class="czi-star-filled font-size-sm text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                @endfor
                                                                @for ($j = 0; $j < 2; $j++)
                                                                    <i class="czi-star font-size-sm text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                @endfor
                                                            @endif
                                                            @if (round($overallRating[0])==2)
                                                                @for ($i = 0; $i < 2; $i++)
                                                                    <i class="czi-star-filled font-size-sm text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                @endfor
                                                                @for ($j = 0; $j < 3; $j++)
                                                                    <i class="czi-star font-size-sm text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                @endfor
                                                            @endif
                                                            @if (round($overallRating[0])==1)
                                                                @for ($i = 0; $i < 4; $i++)
                                                                    <i class="czi-star font-size-sm text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                @endfor
                                                                <i class="czi-star-filled font-size-sm text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                            @endif
                                                            @if (round($overallRating[0])==0)
                                                                @for ($i = 0; $i < 5; $i++)
                                                                    <i class="czi-star font-size-sm text-muted {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                @endfor
                                                            @endif
                                                        </div>
                                                        <span class="d-inline-block align-middle">
                                                    {{$overallRating[0]}} {{\App\CPU\translate('Overall')}} {{\App\CPU\translate('rating')}}
                                                </span>
                                                    </div>
                                                    <div class="col-lg-8 col-md-7 pt-sm-3 pt-md-0">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div
                                                                class="text-nowrap {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}"><span
                                                                    class="d-inline-block align-middle text-muted">{{\App\CPU\translate('5')}}</span><i
                                                                    class="czi-star-filled font-size-xs {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}"></i>
                                                            </div>
                                                            <div class="w-100">
                                                                <div class="progress" style="height: 4px;">
                                                                    <div class="progress-bar bg-success" role="progressbar"
                                                                        style="width: <?php echo $widthRating = ($rating[0] != 0) ? ($rating[0] / $overallRating[1]) * 100 : (0); ?>%;"
                                                                        aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <span
                                                                class="text-muted {{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">
                                                        {{$rating[0]}}
                                                    </span>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div
                                                                class="text-nowrap {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}"><span
                                                                    class="d-inline-block align-middle text-muted">{{\App\CPU\translate('4')}}</span><i
                                                                    class="czi-star-filled font-size-xs {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}"></i>
                                                            </div>
                                                            <div class="w-100">
                                                                <div class="progress" style="height: 4px;">
                                                                    <div class="progress-bar" role="progressbar"
                                                                        style="width: <?php echo $widthRating = ($rating[1] != 0) ? ($rating[1] / $overallRating[1]) * 100 : (0); ?>%; background-color: #a7e453;"
                                                                        aria-valuenow="27" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <span
                                                                class="text-muted {{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">
                                                    {{$rating[1]}}
                                                    </span>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div
                                                                class="text-nowrap {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}"><span
                                                                    class="d-inline-block align-middle text-muted">{{\App\CPU\translate('3')}}</span><i
                                                                    class="czi-star-filled font-size-xs ml-1"></i></div>
                                                            <div class="w-100">
                                                                <div class="progress" style="height: 4px;">
                                                                    <div class="progress-bar" role="progressbar"
                                                                        style="width: <?php echo $widthRating = ($rating[2] != 0) ? ($rating[2] / $overallRating[1]) * 100 : (0); ?>%; background-color: #ffda75;"
                                                                        aria-valuenow="17" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <span
                                                                class="text-muted {{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">
                                                        {{$rating[2]}}
                                                    </span>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div
                                                                class="text-nowrap {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}"><span
                                                                    class="d-inline-block align-middle text-muted">{{\App\CPU\translate('2')}}</span><i
                                                                    class="czi-star-filled font-size-xs {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}"></i>
                                                            </div>
                                                            <div class="w-100">
                                                                <div class="progress" style="height: 4px;">
                                                                    <div class="progress-bar" role="progressbar"
                                                                        style="width: <?php echo $widthRating = ($rating[3] != 0) ? ($rating[3] / $overallRating[1]) * 100 : (0); ?>%; background-color: #fea569;"
                                                                        aria-valuenow="9" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <span
                                                                class="text-muted {{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">
                                                    {{$rating[3]}}
                                                    </span>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="text-nowrap {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}"><span
                                                                    class="d-inline-block align-middle text-muted">{{\App\CPU\translate('1')}}</span><i
                                                                    class="czi-star-filled font-size-xs {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}"></i>
                                                            </div>
                                                            <div class="w-100">
                                                                <div class="progress" style="height: 4px;">
                                                                    <div class="progress-bar bg-danger" role="progressbar"
                                                                        style="width: <?php echo $widthRating = ($rating[4] != 0) ? ($rating[4] / $overallRating[1]) * 100 : (0); ?>%;"
                                                                        aria-valuenow="4" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <span
                                                                class="text-muted {{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">
                                                    {{$rating[4]}}
                                                    </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="mt-4 pb-4 mb-3">
                                                <div class="row pb-4">
                                                    <div class="col-12">
                                                        @foreach($product->reviews as $productReview)
                                                            <div class="single_product_review p-2" style="margin-bottom: 20px">
                                                                <div class="product-review d-flex justify-content-between">
                                                                    <div
                                                                        class="d-flex mb-3 {{Session::get('direction') === "rtl" ? 'pl-5' : 'pr-5'}}">
                                                                        <div
                                                                            class="media media-ie-fix align-items-center {{Session::get('direction') === "rtl" ? 'ml-4 pl-2' : 'mr-4 pr-2'}}">
                                                                            <img style="max-height: 64px;"
                                                                                class="rounded-circle" width="64"
                                                                                onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                                                src="{{asset("storage/app/public/profile")}}/{{(isset($productReview->user)?$productReview->user->image:'')}}"
                                                                                alt="{{isset($productReview->user)?$productReview->user->f_name:'not exist'}}"/>
                                                                            <div
                                                                                class="media-body {{Session::get('direction') === "rtl" ? 'pr-3' : 'pl-3'}}">
                                                                                <h6 class="font-size-sm mb-0">{{isset($productReview->user)?$productReview->user->f_name:'not exist'}}</h6>
                                                                                <div class="d-flex justify-content-between">
                                                                                    <div
                                                                                        class="product_review_rating">{{$productReview->rating}}</div>
                                                                                    <div class="star-rating">
                                                                                        @for($inc=0;$inc<5;$inc++)
                                                                                            @if($inc<$productReview->rating)
                                                                                                <i class="sr-star czi-star-filled active"></i>
                                                                                            @else
                                                                                                <i class="sr-star czi-star"></i>
                                                                                            @endif
                                                                                        @endfor
                                                                                    </div>
                                                                                </div>
                                                                                <div class="font-size-ms text-muted">
                                                                                    {{$productReview->created_at->format('M d Y')}}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <p class="font-size-md mt-3 mb-2">{{$productReview->comment}}</p>
                                                                        @if (!empty(json_decode($productReview->attachment)))
                                                                            @foreach (json_decode($productReview->attachment) as $key => $photo)
                                                                                <img
                                                                                    style="cursor: pointer;border-radius: 5px;border:1px;border-color: #7a6969; height: 67px ; margin-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 5px;"
                                                                                    onclick="showInstaImage('{{asset("storage/app/public/review/$photo")}}')"
                                                                                    class="cz-image-zoom"
                                                                                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                                                    src="{{asset("storage/app/public/review/$photo")}}"
                                                                                    alt="Product review" width="67">
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        @if(count($product->reviews)==0)
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h6 class="text-danger text-center">{{\App\CPU\translate('product_review_not_available')}}</h6>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- end overview section -->

                </div>
            </div>

            <!-- Product thumbnail-->
            <div class="col-lg-5 col-md-5 mt-md-0 mt-sm-3 d-none d-md-block" style="direction: {{ Session::get('direction') }}">
                <div class="cz">
                    <div class="container p-0">
                        <div class="row">
                            <div class="table-responsive ml-1" data-simplebar>
                                <div class="thumblist-frame">
                                    @if($product->images!=null)
                                        @foreach (array_slice(json_decode($product->images), 1, 2) as $key => $photo)
                                            <div class="cz-thumblist d-block">
                                                <a class="mt-0 {{$key==0?'active':''}} d-flex align-items-center justify-content-center "
                                                   href="#image{{$key}}" style="overflow: hidden;">
                                                    <img
                                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                        src="{{asset("storage/product/$photo")}}"
                                                        class="w-100"
                                                        alt="Product thumb">
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- booking card-->
                <div class="booking-card --sticky mt-3">
                    <form id="add-to-cart-form">
                        @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <section class="booking-card__info">
                        <div class="booking-card__info-price">
                            <h5 class="booking-card__info-price-amount">{{\App\CPU\Helpers::currency_converter(
                                $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))
                                )}}
                            </h5>
                            <span class="booking-card__info-price-amount-unit">/ bulan</span>
                        </span>
                        @if($product->discount > 0)
                            <strike class="text-danger ml-2" style="font-size: 10px;">
                                {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                            </strike>
                        @endif
                        </div>

                        @foreach (json_decode($product->choice_options) as $key => $choice)
                            <div class="row flex-start mx-0">
                                <div
                                    class="product-description-label mt-2 {{Session::get('direction') === "rtl" ? 'pl-2' : 'pr-2'}}">{{ $choice->title }}
                                    :
                                </div>
                                <div>
                                    <ul class="list-inline checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-2 mx-1 flex-start"
                                        style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0;">
                                        @foreach ($choice->options as $key => $option)
                                            <div>
                                                <li class="for-mobile-capacity">
                                                    <input class="var" type="radio"
                                                        id="{{ $choice->name }}-{{ $option }}"
                                                        name="{{ $choice->name }}" value="{{ $option }}"
                                                        @if($key == 0) checked @endif >
                                                    <label style="font-size: .6em"
                                                        for="{{ $choice->name }}-{{ $option }}">{{ $option }}</label>
                                                </li>
                                            </div>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach

                        <div class="row flex-start no-gutters d-none mt-2" id="chosen_price_div">
                            <div class="{{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">
                                <div class="product-description-label">{{\App\CPU\translate('total_price')}}:</div>
                            </div>
                            <div>
                                <div class="product-price for-total-price">
                                    <strong id="chosen_price"></strong>
                                </div>
                            </div>

                            <div class="col-12">
                                @if($product['current_stock']<=0)
                                    <h5 class="mt-3" style="color: red">{{\App\CPU\translate('out_of_stock')}}</h5>
                                @endif
                            </div>
                        </div>

                        <div class="booking-card__info-select mt-3">
                            <section class="booking-input-checkin booking-card__info-select-dat w-100">
                                <div class="form-group">
                                    <label for="">Tanggal mulai</label>
                                    <input onclick="checkuser()" name="start_date" id="start_date" type="date" placeholder="Tanggal mulai" class="start_date form-control">
                                </div>
                            </section>
                        </div>
                        <div class="order-summary mt-2 d-none">
                            @include('web-views.products._order-summary')
                        </div>
                        <div class="sewa mt-3">
                            @if ($product->current_stock > 0)
                            <button class="btn btn-success w-100" id="ajukan" type="button" onclick="buy_now('add-to-cart-form')" disabled>
                                Ajukan Sewa
                            </button>
                            @else
                            <button class="btn btn-secondary w-100" type="button" onclick="buy_now()" disabled>
                                Kamar penuh
                            </but
                            @endif
                        </div>
                    </section>
                    </form>
                </div>
                <!-- end booking card-->
            </div>
        </div>
    </div>

    {{--overview--}}


    <!-- Product carousel (You may also like)-->
    <div class="container  mb-3 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        {{-- <div class="flex-between">
            <div class="feature_header">
                <span>{{ \App\CPU\translate('similar_products')}}</span>
            </div>

            <div class="view_all ">
                <div>
                    @php($category=json_decode($product['category_ids']))
                    <a class="btn btn-outline-accent btn-sm viw-btn-a"
                       href="{{route('products',['id'=> $category[0]->id,'data_from'=>'category','page'=>1])}}">{{ \App\CPU\translate('view_all')}}
                        <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'}}"></i>
                    </a>
                </div>
            </div>
        </div> --}}
        <!-- Grid-->
        {{-- <hr class="view_border"> --}}
        <!-- Product-->
        <div class="row mt-4">
            @if (count($relatedProducts)>0)
                @foreach($relatedProducts as $key => $relatedProduct)
                    <div class="col-xl-2 col-sm-3 col-6" style="margin-bottom: 20px">
                        @include('web-views.partials._single-product',['product'=>$relatedProduct])
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-danger text-center">{{\App\CPU\translate('similar')}} {{\App\CPU\translate('product_not_available')}}</h6>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade rtl" id="show-modal-view" tabindex="-1" role="dialog" aria-labelledby="show-modal-image"
         aria-hidden="true" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" style="display: flex;justify-content: center">
                    <button class="btn btn-default"
                            style="border-radius: 50%;margin-top: -25px;position: absolute;{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: -7px;"
                            data-dismiss="modal">
                        <i class="fa fa-close"></i>
                    </button>
                    <img class="element-center" id="attachment-view" src="">
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="user" name="user" value="{{ auth('customer')->id() }}">
    <div class="product-footer d-flex d-md-none">
        <div class="container">
            <div class="row">
                <div class="col-8">
                    <span class="price-foot">
                        {{\App\CPU\Helpers::currency_converter(
                            $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))
                            )}} <span class="month">/ Bulan</s>
                    </span>
                </div>
                <div class="col-4">
                    @if ($product->current_stock > 0)
                    <button type="button" class="btn btn-success px-1 py-2 w-100" onclick="show_date()">
                        Ajukan Sewa
                    </button>
                    @else
                    <button disabled type="button" class="btn btn-danger px-1 py-2 w-100" data-toggle="modal" data-target="#exampleModal">
                        Penuh
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 3 !mportant;">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pilih tanggal masuk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form id="add-to-cart">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Tanggal mulai</label>
                        <input name="start_date" id="start_" type="date" placeholder="Tanggal mulai" class="start_date form-control">
                    </div>
                    <div class="order-summary mt-2 d-none">
                        @include('web-views.products._order-summary')
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="buy_now('add-to-cart')">Booking</button>
                </div>
                </form>
            </div>
            </div>
        </div>
@endsection

@push('script')
    <script>
        function show_date(){
            var user = $('#user').val();
            if(!user){
                location.href = "{{route('customer.auth.login')}}";
            }else{
                $('#exampleModal').modal('show');
            }
        }
        function checkuser(){
            var user = $('#user').val();
            if(!user){
                location.href = "{{route('customer.auth.login')}}";
            }
        }

        function selectDate(){
            console.log('select date')
        }
        $(document).ready(function(){
            var h = $('#cz-preview').outerHeight()
            var tinggi = h/2;
            var margin = tinggi - 5
            console.log('height',margin)
            $('.cz-thumblist').attr('style', 'min-height: 195px; height:' + margin + 'px')
        })
    </script>
    <script type="text/javascript">
        $(".start_date").on('change', function(){
            $('.order-summary').removeClass('d-none');
            $('#ajukan').removeAttr('disabled')
        })

        cartQuantityInitialize();

        // getVariantPrice();
        // $('#add-to-cart-form input.val').on('change', function () {
        //     getVariantPrice();
        // });

        function showInstaImage(link) {
            $("#attachment-view").attr("src", link);
            $('#show-modal-view').modal('toggle')
        }
    </script>

    {{-- Messaging with shop seller --}}
    <script>
        $('#contact-seller').on('click', function (e) {
            // $('#seller_details').css('height', '200px');
            $('#seller_details').animate({'height': '276px'});
            $('#msg-option').css('display', 'block');
        });
        $('#sendBtn').on('click', function (e) {
            e.preventDefault();
            let msgValue = $('#msg-option').find('textarea').val();
            let data = {
                message: msgValue,
                shop_id: $('#msg-option').find('textarea').attr('shop-id'),
                seller_id: $('.msg-option').find('.seller_id').attr('seller-id'),
            }
            if (msgValue != '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "post",
                    url: '{{route('messages_store')}}',
                    data: data,
                    success: function (respons) {
                        console.log('send successfully');
                    }
                });
                $('#chatInputBox').val('');
                $('#msg-option').css('display', 'none');
                $('#contact-seller').find('.contact').attr('disabled', '');
                $('#seller_details').animate({'height': '125px'});
                $('#go_to_chatbox').css('display', 'block');
            } else {
                console.log('say something');
            }
        });
        $('#cancelBtn').on('click', function (e) {
            e.preventDefault();
            $('#seller_details').animate({'height': '114px'});
            $('#msg-option').css('display', 'none');
        });
    </script>

    <script type="text/javascript"
            src="https://platform-api.sharethis.com/js/sharethis.js#property=5f55f75bde227f0012147049&product=sticky-share-buttons"
            async="async"></script>
@endpush
