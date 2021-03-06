<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>
        @yield('title')
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="180x180"
    href="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}">

    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/simplebar/dist/simplebar.min.css"/>
    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/tiny-slider/dist/tiny-slider.css"/>
    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/drift-zoom/dist/drift-basic.min.css"/>
    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/lightgallery.js/dist/css/lightgallery.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/toastr.css"/>
    <!-- Main Theme Styles + Bootstrap-->
    <link rel="stylesheet" media="screen" href="{{asset('public/assets/front-end')}}/css/theme.min.css">
    <link rel="stylesheet" media="screen" href="{{asset('public/assets/front-end')}}/css/slick.css">
    <link rel="stylesheet" media="screen" href="{{asset('public/assets/front-end')}}/css/font-awesome.min.css">
    <!--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">-->
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/toastr.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/front-end')}}/css/master.css"/>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Titillium+Web:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/front-end')}}/css/newProductsss.css"/>
    <link rel="stylesheet" href="{{asset('assets/front-end')}}/css/new-detailsCard.css"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @stack('css_or_js')

    <link rel="stylesheet" href="{{asset('assets/front-end')}}/css/home.css"/>
    <link rel="stylesheet" href="{{asset('assets/front-end')}}/css/responsive1.css"/>

    {{--dont touch this--}}
    <meta name="_token" content="{{csrf_token()}}">
    {{--dont touch this--}}
    <!--to make http ajax request to https-->
    <!--<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">-->
    <style>
        body {
            background-color: #f7f8fa94;
            font-family: 'Poppins', sans-serif;
        }
        .label-cashback {
            position: absolute;
            background-color: #dededea3;
            padding: 2px 8px 2px 10px;
            top: 0;
            right: 0;
            font-size: 12px;
            font-weight: 600;
            color: #e51b23 !important;
            letter-spacing: 1px;
            border-radius: 0 10px 0 10px;
        }
        .rtl {
            direction: {{ Session::get('direction') }};
        }
        .password-toggle-btn .password-toggle-indicator:hover {
            color: {{$web_config['primary_color']}};
        }
        .password-toggle-btn .custom-control-input:checked ~ .password-toggle-indicator {
            color: {{$web_config['secondary_color']}};
        }
        .dropdown-item:hover, .dropdown-item:focus {
            color: {{$web_config['primary_color']}};
            text-decoration: none;
            background-color: rgba(0, 0, 0, 0)
        }
        .dropdown-item.active, .dropdown-item:active {
            color: {{$web_config['secondary_color']}};
            text-decoration: none;
            background-color: rgba(0, 0, 0, 0)
        }
        .topbar a {
            color: black !important;
        }
        .navbar-light .navbar-tool-icon-box {
            color: {{$web_config['primary_color']}};
        }
        .search_button {
            background-color: {{$web_config['primary_color']}};
            border: none;
        }
        .nav-link {
            color: white !important;
        }
        .navbar-stuck-menu {
            background-color: {{$web_config['primary_color']}};
            min-height: 0;
            padding-top: 0;
            padding-bottom: 0;
        }
        .mega-nav {
            background: white;
            position: relative;
            margin-top: 6px;
            line-height: 17px;
            width: 304px;
            border-radius: 3px;
        }
        .mega-nav .nav-item .nav-link {
            padding-top: 11px !important;
            color: {{$web_config['primary_color']}}                           !important;
            font-size: 20px;
            font-weight: 600;
            padding-left: 20px !important;
        }
        .nav-item .dropdown-toggle::after {
            margin-left: 20px !important;
        }
        .navbar-tool-text {
            padding-left: 5px !important;
            font-size: 16px;
        }
        .navbar-tool-text > small {
            color: #4b566b !important;
        }
        .modal-header .nav-tabs .nav-item .nav-link {
            color: black !important;
            /*border: 1px solid #E2F0FF;*/
        }
        .checkbox-alphanumeric::after,
        .checkbox-alphanumeric::before {
            content: '';
            display: table;
        }
        .checkbox-alphanumeric::after {
            clear: both;
        }
        .checkbox-alphanumeric input {
            left: -9999px;
            position: absolute;
        }
        .checkbox-alphanumeric label {
            width: 2.25rem;
            height: 2.25rem;
            float: left;
            padding: 0.375rem 0;
            margin-right: 0.375rem;
            display: block;
            color: #818a91;
            font-size: 0.875rem;
            font-weight: 400;
            text-align: center;
            background: transparent;
            text-transform: uppercase;
            border: 1px solid #e6e6e6;
            border-radius: 2px;
            -webkit-transition: all 0.3s ease;
            -moz-transition: all 0.3s ease;
            -o-transition: all 0.3s ease;
            -ms-transition: all 0.3s ease;
            transition: all 0.3s ease;
            transform: scale(0.95);
        }
        .checkbox-alphanumeric-circle label {
            border-radius: 100%;
        }
        .checkbox-alphanumeric label > img {
            max-width: 100%;
        }
        .checkbox-alphanumeric label:hover {
            cursor: pointer;
            border-color: {{$web_config['primary_color']}};
        }
        .checkbox-alphanumeric input:checked ~ label {
            transform: scale(1.1);
            border-color: red !important;
        }
        .checkbox-alphanumeric--style-1 label {
            width: auto;
            padding-left: 1rem;
            padding-right: 1rem;
            border-radius: 2px;
        }
        .d-table.checkbox-alphanumeric--style-1 {
            width: 100%;
        }
        .d-table.checkbox-alphanumeric--style-1 label {
            width: 100%;
        }
        /* CUSTOM COLOR INPUT */
        .checkbox-color::after,
        .checkbox-color::before {
            content: '';
            display: table;
        }
        .checkbox-color::after {
            clear: both;
        }
        .checkbox-color input {
            left: -9999px;
            position: absolute;
        }
        .checkbox-color label {
            width: 2.25rem;
            height: 2.25rem;
            float: left;
            padding: 0.375rem;
            margin-right: 0.375rem;
            display: block;
            font-size: 0.875rem;
            text-align: center;
            opacity: 0.7;
            border: 2px solid #d3d3d3;
            border-radius: 50%;
            -webkit-transition: all 0.3s ease;
            -moz-transition: all 0.3s ease;
            -o-transition: all 0.3s ease;
            -ms-transition: all 0.3s ease;
            transition: all 0.3s ease;
            transform: scale(0.95);
        }
        .checkbox-color-circle label {
            border-radius: 100%;
        }

        .checkbox-color label:hover {
            cursor: pointer;
            opacity: 1;
        }

        .checkbox-color input:checked ~ label {
            transform: scale(1.1);
            opacity: 1;
            border-color: red !important;
        }

        .checkbox-color input:checked ~ label:after {
            content: "\f121";
            font-family: "Ionicons";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .card-img-top img, figure {
            max-width: 200px;
            max-height: 200px !important;
            vertical-align: middle;
        }

        .product-card {
            box-shadow: 1px 1px 6px #00000014;
            border-radius: 5px;
        }

        .product-card .card-header {
            text-align: center;
            background: white 0% 0% no-repeat padding-box;
            border-radius: 5px 5px 0px 0px;
            border-bottom: white !important;
        }

        .product-title {
            font-family: 'Roboto', sans-serif !important;
            font-weight: 400 !important;
            font-size: 22px !important;
            color: #000000 !important;
        }

        .feature_header span {
            font-weight: 700;
            font-size: 25px;
            text-transform: uppercase;
        }

        html[dir="ltr"] .feature_header span {
            padding-right: 15px;
        }

        html[dir="rtl"] .feature_header span {
            padding-left: 15px;
        }
        .label-vendor {
            position: absolute;
            background-color: {{ $web_config['primary_color'] }};
            padding: 2px 8px 2px 10px;
            top: 147px;
            right: 0;
            font-size:12px;
            color: #fff !important;
            letter-spacing: 1px;
            border-radius: 10px 0 0 0;
        }

        @media (max-width: 768px ) {
            .btn-scroll-top.show {
                right: 1rem;
                display: none !important;
            }
            .unf-bottomnav {
                padding: 10px 20px 10px 20px;
                height: 50px;
                position: fixed;
                bottom: 0px;
                width: 100%;
                padding-bottom: 50px;
                background-color: var(--N0,#FFFFFF);
                box-shadow: rgb(108 114 124 / 16%) 0px -2px 4px 0px;
                z-index: 20;
                display: flex;
                max-width: 100vw;
                margin: 0px auto;
                -webkit-box-pack: justify;
                justify-content: space-between;
                align-items: flex-start;
            }
            .css-11rf802 {
                padding: 4px 0px;
                display: flex;
                -webkit-box-align: center;
                align-items: center;
                flex-flow: column nowrap;
                justify-content: space-around;
                font-weight: 400;
                font-size: 10px;
                line-height: 16px;
                color: rgba(49, 53, 59, 0.68);
                text-decoration: initial;
                white-space: nowrap;
            }
            .feature_header {
                margin-top: 0;
                display: flex;
                justify-content: flex-start !important;

            }

            .store-contents {
                justify-content: center;
            }

            .feature_header span {
                padding-right: 0;
                padding-left: 0;
                font-weight: 700;
                font-size: 25px;
                text-transform: uppercase;
            }

            .view_border {
                margin: 16px 0px;
                border-top: 2px solid #E2F0FF !important;
            }

        }

        .scroll-bar {
            max-height: calc(100vh - 100px);
            overflow-y: auto !important;
        }

        ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px white;
            border-radius: 5px;
        }

        ::-webkit-scrollbar {
            width: 3px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(194, 194, 194, 0.38) !important;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: {{$web_config['secondary_color']}}        !important;
        }

        .mobileshow {
            display: none;
        }

        @media screen and (max-width: 500px) {
            .mobileshow {
                display: block;
            }
            .label-vendor {
                top: 108px;
            }
        }

        [type="radio"] {
            border: 0;
            clip: rect(0 0 0 0);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px;
        }

        [type="radio"] + span:after {
            content: '';
            display: inline-block;
            width: 1.1em;
            height: 1.1em;
            vertical-align: -0.10em;
            border-radius: 1em;
            border: 0.35em solid #fff;
            box-shadow: 0 0 0 0.10em{{$web_config['secondary_color']}};
            margin-left: 0.75em;
            transition: 0.5s ease all;
        }

        [type="radio"]:checked + span:after {
            background: {{$web_config['secondary_color']}};
            box-shadow: 0 0 0 0.10em{{$web_config['secondary_color']}};
        }

        [type="radio"]:focus + span::before {
            font-size: 1.2em;
            line-height: 1;
            vertical-align: -0.125em;
        }


        .checkbox-color label {
            box-shadow: 0px 3px 6px #0000000D;
            border: none;
            border-radius: 3px !important;
            max-height: 35px;
        }

        .checkbox-color input:checked ~ label {
            transform: scale(1.1);
            opacity: 1;
            border: 1px solid #ffb943 !important;
        }

        .checkbox-color input:checked ~ label:after {
            font-family: "Ionicons", serif;
            position: absolute;
            content: "\2713" !important;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .navbar-tool .navbar-tool-label {
            position: absolute;
            top: -.3125rem;
            right: -.3125rem;
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 50%;
            background-color: {{$web_config['secondary_color']}}!important;
            color: #fff;
            font-size: .75rem;
            font-weight: 500;
            text-align: center;
            line-height: 1.25rem;
        }

        .btn-primary {
            color: #fff;
            background-color: {{$web_config['primary_color']}}!important;
            border-color: {{$web_config['primary_color']}}!important;
        }

        .btn-primary:hover {
            color: #fff;
            background-color: {{$web_config['primary_color']}}!important;
            border-color: {{$web_config['primary_color']}}!important;
        }

        .btn-secondary {
            background-color: {{$web_config['secondary_color']}}!important;
            border-color: {{$web_config['secondary_color']}}!important;
        }

        .btn-outline-accent:hover {
            color: #fff;
            background-color: {{$web_config['primary_color']}};
            border-color: {{$web_config['primary_color']}};
        }

        .btn-outline-accent {
            color: {{$web_config['primary_color']}};
            border-color: {{$web_config['primary_color']}};
        }

        .text-accent {
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
            font-size: 18px;
            color: {{$web_config['primary_color']}};
        }

        a:hover {
            color: {{$web_config['secondary_color']}};
            text-decoration: none
        }

        .active-menu {
            color: {{$web_config['secondary_color']}}!important;
        }

        .page-item.active > .page-link {
            box-shadow: 0 0.5rem 1.125rem -0.425rem{{$web_config['primary_color']}}


        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: {{$web_config['primary_color']}};
            border-color: rgba(0, 0, 0, 0)
        }

        .btn-outline-accent:not(:disabled):not(.disabled):active, .btn-outline-accent:not(:disabled):not(.disabled).active, .show > .btn-outline-accent.dropdown-toggle {
            color: #fff;
            background-color: {{$web_config['secondary_color']}};
            border-color: {{$web_config['secondary_color']}};
        }

        .btn-outline-primary {
            color: {{$web_config['primary_color']}};
            border-color: {{$web_config['primary_color']}};
        }

        .btn-outline-primary:hover {
            color: #fff;
            background-color: {{$web_config['secondary_color']}};
            border-color: {{$web_config['secondary_color']}};
        }

        .btn-outline-primary:focus, .btn-outline-primary.focus {
            box-shadow: 0 0 0 0{{$web_config['secondary_color']}};
        }

        .btn-outline-primary.disabled, .btn-outline-primary:disabled {
            color: #6f6f6f;
            background-color: transparent
        }

        .btn-outline-primary:not(:disabled):not(.disabled):active, .btn-outline-primary:not(:disabled):not(.disabled).active, .show > .btn-outline-primary.dropdown-toggle {
            color: #fff;
            background-color: {{$web_config['primary_color']}};
            border-color: {{$web_config['primary_color']}};
        }

        .btn-outline-primary:not(:disabled):not(.disabled):active:focus, .btn-outline-primary:not(:disabled):not(.disabled).active:focus, .show > .btn-outline-primary.dropdown-toggle:focus {
            box-shadow: 0 0 0 0{{$web_config['primary_color']}};
        }

        .feature_header span {
            background-color: transparent !important
        }

        .discount-top-f {
            position: absolute;
        }

        html[dir="ltr"] .discount-top-f {
            left: 0;
        }

        html[dir="rtl"] .discount-top-f {
            right: 0;
        }

        .for-discoutn-value {
            background: {{$web_config['primary_color']}};

        }

        .czi-star-filled {
            color: #1baa56 !important;
        }

        .rc-label {
            color: #404040;
            margin: 0 0 0 4px;
        }

        .bg-c-text--label-1{
            font-size: 12px;
            font-weight: 500;
            line-height: 20px;
        }

        .flex-start {
            display: flex;
            justify-content: flex-start;
        }

        .flex-center {
            display: flex;
            justify-content: center;
        }

        .flex-around {
            display: flex;
            justify-content: space-around;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
        }

        .row-reverse {
            display: flex;
            flex-direction: row-reverse;
        }

        .count-value {
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 50%;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 500;
            text-align: center;
            line-height: 1.25rem;
        }
    </style>

    <!--for product-->
    <style>
        .mobile-margin{
            height: 60px;
            width: 100%
        }
        .capitalize {
            text-transform: capitalize;
        }
        .rc-facilities p{
            text-overflow: ellipsis;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            white-space: normal;
        }
        .rc-overview__label {
            font-size: 12px;
            padding: 4px 8px;
        }
        .rc-info__address{
            font-size: 12px;
            font-weight: 500;
            text-transform: capitalize !important;
            /* line-height: 20px; */
            font-family: "Poppins", sans-serif
        }

        .bg-c-label {
            display: inline-block;
            border-radius: 14px;
            white-space: nowrap;
            color: #404040;
            background-color: #fff;
            border-width: 0;
            box-shadow: inset 0 0 0 1px #dadada;
            font-weight: 600;
        }

        .room-card_overview{
            align-items: center;
            color: #1baa56;
            display: flex;
            margin: 0 0 0 8px;
        }
        .stock-out {
            position: absolute;
            top: 40% !important;
            color: white !important;
            font-weight: 900;
            font-size: 15px;
        }

        html[dir="ltr"] .stock-out {
            left: 35% !important;
        }

        html[dir="rtl"] .stock-out {
            right: 35% !important;
        }

        .product-card {
            height: 100%;
        }

        .badge-style {
            left: 75% !important;
            margin-top: -2px !important;
            background: transparent !important;
            color: black !important;
        }

        html[dir="ltr"] .badge-style {
            right: 0 !important;
        }

        .dropdown-menu {
            min-width: 304px !important;
            margin-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: -8px !important;
            border-top-left-radius: 0px;
            border-top-right-radius: 0px;
        }
        html[dir="rtl"] .badge-style {
            left: 0 !important;
        }
    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<!-- Body-->
<body class="toolbar-enabled">
<!-- Sign in / sign up modal-->
@include('layouts.front-end.partials._modals')
<!-- Navbar-->
<!-- Quick View Modal-->
@include('layouts.front-end.partials._quick-view-modal')
<!-- Navbar Electronics Store-->
@include('layouts.front-end.partials._header')
@include('layouts.front-end.partials._mobile-header')
<!-- Page title-->
{{--loader--}}
<div class="container">
    <div class="row">
        <div class="col-12" style="width:85%;position: fixed;z-index: 9999;display: flex;align-items: center;justify-content: center;">
            <div id="loading" style="display: none">
                <img width="200"
                src="{{asset('storage/company')}}/{{\App\CPU\Helpers::get_business_settings('loader_gif')}}"
                onerror="this.src='{{asset('public/assets/front-end/img/loader.gif')}}'">
            </div>
        </div>
    </div>
</div>
{{--loader--}}

<!-- Page Content-->
@yield('content')

<!-- Footer-->
<!-- Footer-->
@if (Route::currentRouteName() != 'wishlists' && Route::currentRouteName() != 'account-oder' && Route::currentRouteName() != 'chat-with-seller' && Route::currentRouteName() != 'user-account' && Route::currentRouteName() != 'account-verify')
@include('layouts.front-end.partials._footer')
@endif
@if (Route::is('home'))
<div class="footer-mobile d-block d-md-none">
    @include('layouts.front-end.partials._mobile_footer')
</div>
@endif
<!-- Toolbar for handheld devices-->
<!--<div class="cz-handheld-toolbar" id="toolbar">
    {{--@include('layouts.front-end.partials._toolbar')--}}
    </div>-->

<!-- Back To Top Button-->
<a class="btn-scroll-top" href="#top" data-scroll>
    <span class="btn-scroll-top-tooltip text-muted font-size-sm mr-2">Top</span><i
        class="btn-scroll-top-icon czi-arrow-up"> </i>
</a>

<!-- Vendor scrits: js libraries and plugins-->
@if (Request::segment(1) !== 'products' && Request::segment(1) !== 'jobs')
<script src="{{asset('public/assets/front-end')}}/vendor/jquery/dist/jquery.slim.min.js"></script>
<script src="{{asset('public/assets/front-end')}}/vendor/jquery/dist/jquery-2.2.4.min.js"></script>
@endif
<script src="{{asset('public/assets/front-end')}}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script
src="{{asset('public/assets/front-end')}}/vendor/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
<script src="{{asset('public/assets/front-end')}}/vendor/simplebar/dist/simplebar.min.js"></script>
<script src="{{asset('public/assets/front-end')}}/vendor/tiny-slider/dist/min/tiny-slider.js"></script>
<script src="{{asset('public/assets/front-end')}}/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

<script src="{{asset('public/assets/front-end')}}/vendor/drift-zoom/dist/Drift.min.js"></script>
<script src="{{asset('public/assets/front-end')}}/vendor/lightgallery.js/dist/js/lightgallery.min.js"></script>
<script src="{{asset('public/assets/front-end')}}/vendor/lg-video.js/dist/lg-video.min.js"></script>
{{--Toastr--}}

<script src={{asset("public/assets/back-end/js/toastr.js")}}></script>
<!-- Main theme script-->
<script src="{{asset('public/assets/front-end')}}/js/theme.min.js"></script>
<script src="{{asset('public/assets/front-end')}}/js/slick.min.js"></script>

<script src="{{asset('public/assets/front-end')}}/js/sweet_alert.js"></script>
{{--Toastr--}}
<script src={{asset("public/assets/back-end/js/toastr.js")}}></script>
{!! Toastr::message() !!}
<script>
    function addWishlist(product_id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('store-wishlist')}}",
            method: 'POST',
            data: {
                product_id: product_id
            },
            success: function (data) {
                if (data.value == 1) {
                    Swal.fire({
                        position: 'top-end',
                        type: 'success',
                        title: data.success,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('.countWishlist').html(data.count);
                    $('.countWishlist-' + product_id).text(data.product_count);
                    $('.tooltip').html('');

                } else if (data.value == 2) {
                    Swal.fire({
                        type: 'info',
                        title: 'WishList',
                        text: data.error
                    });
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'WishList',
                        text: data.error
                    });
                }
            }
        });
    }

    function removeWishlist(product_id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('delete-wishlist')}}",
            method: 'POST',
            data: {
                id: product_id
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                Swal.fire({
                    type: 'success',
                    title: 'WishList',
                    text: data.success
                });
                $('.countWishlist').html(data.count);
                $('#set-wish-list').html(data.wishlist);
                $('.tooltip').html('');
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }

    function quickView(product_id) {
        $.get({
            url: '{{route('quick-view')}}',
            dataType: 'json',
            data: {
                product_id: product_id
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                console.log("success...")
                $('#quick-view').modal('show');
                $('#quick-view-modal').empty().html(data.view);
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }

    function addToCart(val) {
        var form_id = val;
        console.log(val)
        if(val == 'add-to-cart'){
            if (checkAddToCartValidity()) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{ route('cart.add') }}',
                    data: $('#' + form_id).serializeArray(),
                    beforeSend: function () {
                        $('#loading').show();
                    },
                    success: function (response) {
                        console.log(response);
                        if (response.status == 1) {
                            // updateNavCart();
                            toastr.success(response.message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            $('.call-when-done').click();
                            return false;
                        } else if (response.status == 0) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Cart',
                                text: response.message
                            });
                            return false;
                        }
                    },
                    complete: function () {
                        $('#loading').hide();
                        // location.href = "{{url('/shop-cart')}}";
                    }
                });
            } else {
                Swal.fire({
                    type: 'info',
                    title: 'Cart',
                    text: '{{\App\CPU\translate('please_choose_all_the_options')}}'
                });
            }
        }else{
            if (checkAddToCartValidityMobile()) {
                // console.log('data', $('#' + form_id))
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{ route('cart.add') }}',
                    data: $('#' + form_id).serializeArray(),
                    beforeSend: function () {
                        $('#loading').show();
                    },
                    success: function (response) {
                        // console.log('cart',response);
                        if (response.status == 1) {
                            // updateNavCart();
                            // toastr.success(response.message, {
                            //     CloseButton: true,
                            //     ProgressBar: true
                            // });
                            $('.call-when-done').click();
                            return false;
                        } else if (response.status == 0) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Cart',
                                text: response.message
                            });
                            return false;
                        }
                    },
                    complete: function () {
                        $('#loading').hide();
                        location.href = "{{url('/shop-cart')}}";
                    }
                });
            } else {
                Swal.fire({
                    type: 'info',
                    title: 'Cart',
                    text: '{{\App\CPU\translate('please_choose_all_the_options')}}'
                });
            }
        }
    }

    function buy_now(val) {
        // console.log(val);
        addToCart(val);
    }

    function currency_change(currency_code) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '{{route('currency.change')}}',
            data: {
                currency_code: currency_code
            },
            success: function (data) {
                toastr.success('{{\App\CPU\translate('Currency changed to')}}' + data.name);
                location.reload();
            }
        });
    }

    function removeFromCart(key) {
        $.post('{{ route('cart.remove') }}', {_token: '{{ csrf_token() }}', key: key}, function (response) {
            console.log(response)
            updateNavCart();
            $('#cart-summary').empty().html(response.data);
            toastr.info('{{\App\CPU\translate('Item has been removed from cart')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        });
    }

    function updateNavCart() {
        $.post('{{route('cart.nav-cart')}}', {_token: '{{csrf_token()}}'}, function (response) {
            $('#cart_items').html(response.data);
        });
    }

    function cartQuantityInitialize() {
        $('.btn-number').click(function (e) {
            e.preventDefault();

            fieldName = $(this).attr('data-field');
            type = $(this).attr('data-type');
            var input = $("input[name='" + fieldName + "']");
            var currentVal = parseInt(input.val());

            if (!isNaN(currentVal)) {
                if (type == 'minus') {

                    if (currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('min')) {
                        $(this).attr('disabled', true);
                    }

                } else if (type == 'plus') {

                    if (currentVal < input.attr('max')) {
                        input.val(currentVal + 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('max')) {
                        $(this).attr('disabled', true);
                    }

                }
            } else {
                input.val(0);
            }
        });

        $('.input-number').focusin(function () {
            $(this).data('oldValue', $(this).val());
        });

        $('.input-number').change(function () {

            minValue = parseInt($(this).attr('min'));
            maxValue = parseInt($(this).attr('max'));
            valueCurrent = parseInt($(this).val());

            var name = $(this).attr('name');
            if (valueCurrent >= minValue) {
                $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Cart',
                    text: '{{\App\CPU\translate('Sorry, the minimum value was reached')}}'
                });
                $(this).val($(this).data('oldValue'));
            }
            if (valueCurrent <= maxValue) {
                $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Cart',
                    text: '{{\App\CPU\translate('Sorry, stock limit exceeded')}}.'
                });
                $(this).val($(this).data('oldValue'));
            }


        });
        $(".input-number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    }

    function updateQuantity(key, element) {
        $.post('<?php echo e(route('cart.updateQuantity')); ?>', {
            _token: '<?php echo e(csrf_token()); ?>',
            key: key,
            quantity: element.value
        }, function (data) {
            updateNavCart();
            $('#cart-summary').empty().html(data);
        });
    }

    function updateCartQuantity(key) {
        var quantity = $("#cartQuantity" + key).children("option:selected").val();
        $.post('{{route('cart.updateQuantity')}}', {
            _token: '{{csrf_token()}}',
            key: key,
            quantity: quantity
        }, function (response) {
            if (response.status == 0) {
                toastr.error(response.message, {
                    CloseButton: true,
                    ProgressBar: true
                });
                $("#cartQuantity" + key).val(response['qty']);
            } else {
                updateNavCart();
                $('#cart-summary').empty().html(response);
            }
        });
    }

    function numberFormat(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function usePoin(val){
        var switchVal = $('.switchPoin').val()
        if(switchVal == 'on'){
            $('.switchPoin').val('off')

        }
        if(switchVal == 'off'){
            $('.switchPoin').val('on')
        }

        if($('.switchPoin').val() == 'on'){
            $('input[name="pakai"]').val(1)
            var poin = $('#cart-poin .cart_value').text().replace(/[^\d]/g, '')
            var total = $('#total-val').text().replace(/[^\d]/g, '')
            var result = total - poin
            $('#total-val').text('Rp.' + numberFormat(result))
        }

        if($('.switchPoin').val() == 'off'){
            $('input[name="pakai"]').val(0)
            var poin = $('#cart-poin .cart_value').text().replace(/[^\d]/g, '')
            var total = $('#total-val').text().replace(/[^\d]/g, '')
            console.log('offPoin', poin)
            console.log('offTotal', total)
            var result = parseInt(total) + parseInt(poin)
            $('#total-val').text('Rp.' + numberFormat(result))
        }
    }

    function usePoinMobile(val){
        var switchVal = $('.switchPoinMobile').val()
        if(switchVal == 'on'){
            $('.switchPoinMobile').val('off')

        }
        if(switchVal == 'off'){
            $('.switchPoinMobile').val('on')
        }

        if($('.switchPoinMobile').val() == 'on'){
            $('input[name="usePoin"]').val(1)
            var poin = $('#cart-poin-mobile .cart_value').text().replace(/[^\d]/g, '')
            var total = $('#total-val-mobile').text().replace(/[^\d]/g, '')
            var result = total - poin
            $('#total-val-mobile').text('Rp.' + numberFormat(result))
        }

        if($('.switchPoinMobile').val() == 'off'){
            $('input[name="usePoin"]').val(0)
            var poin = $('#cart-poin-mobile .cart_value').text().replace(/[^\d]/g, '')
            var total = $('#total-val-mobile').text().replace(/[^\d]/g, '')
            console.log('offPoin', poin)
            console.log('offTotal', total)
            var result = parseInt(total) + parseInt(poin)
            $('#total-val-mobile').text('Rp.' + numberFormat(result))
        }
    }

    $('#add-to-cart-form input.var').on('change', function () {
        console.log('work')
        getVariantPrice();
    });

    function getVariantPrice() {
        if (checkAddToCartValidity()) {
            console.log('getVariant')
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{ route('cart.variant_price') }}',
                data: $('#add-to-cart-form').serializeArray(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    console.log(data)
                    $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                    $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                    $('#cart-price .cart_value').html(data.real);
                    $('#set-tax-amount').html(data.tax);
                    $('#set-discount-amount').html(data.discount);
                    $('#cart-discount .cart_value').html(data.discount);
                    $('#cart-tax .cart_value').html(data.tax);
                    $('#cart-deposit .cart_value').html(data.deposit);
                    $('#cart-total .cart_value').html(data.price);
                    $('#available-quantity').html(data.quantity);
                    $('.cart-qty-field').attr('max', data.quantity);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }
    }

    function checkAddToCartValidity() {
        var names = {};
        $('#add-to-cart-form input.var:radio').each(function () { // find unique names
            names[$(this).attr('name')] = true;
        });
        var count = 0;
        $.each(names, function () { // then count them
            count++;
        });
        if ($('input.var:radio:checked').length == count) {
            return true;
        }
        return false;
    }

    // Mobile Summary

    $('#add-to-cart-mobile input.var-mobile').on('change', function () {
        console.log('work')
        getVariantPriceMobile();
    });

    function getVariantPriceMobile() {
        var res = checkAddToCartValidityMobile();
        console.log('getVariant', res)
        if (checkAddToCartValidityMobile()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{ route('cart.variant_price') }}',
                data: $('#add-to-cart-mobile').serializeArray(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    console.log(data)
                    $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                    $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                    $('#cart-price .cart_value').html(data.real);
                    $('#set-tax-amount').html(data.tax);
                    $('#set-discount-amount').html(data.discount);
                    $('#cart-discount .cart_value').html(data.discount);
                    $('#cart-tax .cart_value').html(data.tax);
                    $('#cart-total .cart_value').html(data.price);
                    $('#available-quantity').html(data.quantity);
                    $('.cart-qty-field').attr('max', data.quantity);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }
    }

    function checkAddToCartValidityMobile() {
        var names = {};
        $('#add-to-cart-mobile input.var-mobile:radio').each(function () { // find unique names
            names[$(this).attr('name')] = true;
        });
        var count = 0;
        $.each(names, function () { // then count them
            count++;
        });
        if ($('input.var-mobile:radio:checked').length == count) {
            // console.log('true')
            return true;
        }
        return false;
    }

    @if(Request::is('/') &&  \Illuminate\Support\Facades\Cookie::has('popup_banner')==false)
    $(document).ready(function () {
        $('#popup-modal').appendTo("body").modal('show');
    });
    @php(\Illuminate\Support\Facades\Cookie::queue('popup_banner', 'off', 1))
    @endif

    $(".clickable").click(function () {
        window.location = $(this).find("a").attr("href");
        return false;
    });
</script>

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif

<script>
    function couponCode() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: '{{ route('coupon.apply') }}',
            data: $('#coupon-code-ajax').serializeArray(),
            success: function (data) {
                if (data.status == 1) {
                    let ms = data.messages;
                    ms.forEach(
                        function (m, index) {
                            toastr.success(m, index, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    );
                } else {
                    let ms = data.messages;
                    ms.forEach(
                        function (m, index) {
                            toastr.error(m, index, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    );
                }
                setInterval(function () {
                    location.reload();
                }, 2000);
            }
        });
    }

    jQuery(".search-bar-input").keyup(function () {
        $(".search-card").css("display", "block");
        let name = $("#search-bar").val();
        console.log('name', name)
        if (name.length > 0) {
            $.get({
                url: '{{url('/')}}/searched-products',
                dataType: 'json',
                data: {
                    name: name
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    console.log('data', data.result)
                    $('.search-result-box').empty().html(data.result)
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        } else {
            $('.search-result-box').empty();
        }
    });

    jQuery(".search-bar-input-mobile").keyup(function () {
        $(".search-card").css("display", "block");
        let name = $(".search-bar-input-mobile").val();
        console.log('for', name)
        if (name.length > 0) {
            $.get({
                url: '{{url('/')}}/searched-products_mobile',
                dataType: 'json',
                data: {
                    name: name
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('.search-result-mobile').empty().html(data.result)
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        } else {
            $('.search-result-mobile').empty();
        }
    });

    jQuery(document).mouseup(function (e) {
        var container = $(".search-card");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.hide();
        }
    });

    const img = document.getElementByTagName("img")
    img.addEventListener("error", function (event) {
        event.target.src = '{{asset('public/assets/front-end/img/image-place-holder.png')}}';
        event.onerror = null
    })

    function route_alert(route, message) {
        Swal.fire({
            title: '{{\App\CPU\translate('Are you sure')}}?',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '{{$web_config['primary_color']}}',
            cancelButtonText: 'No',
            confirmButtonText: 'Yes',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = route;
            }
        })
    }
</script>
@stack('script')

</body>
</html>
