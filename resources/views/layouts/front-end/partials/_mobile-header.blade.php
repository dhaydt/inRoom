{{-- navabr / _header --}}
<style>
    #nav-global-location-slot {
        border: 2px solid transparent;
        padding: 10px;
        transition: .3s;
        cursor: pointer;
        border-radius: 4px;
    }

    #nav-global-location-slot:hover {
        border: 2px solid #0f0f0f;
    }

    .nav-line-1.nav-progressive-content {
        font-size: 14px;
        line-height: 14px;
        transition: .3s;
        height: 14px;
        color: #9d9d9d;
        font-weight: 700;
    }

    .nav-line-2.nav-progressive-content {
        font-size: 16px;
        font-weight: 700;
        transition: .3s;
    }

    .card-body.search-result-box {
        overflow: scroll;
        height: 400px;
        overflow-x: hidden;
    }

    .active .seller {
        font-weight: 700;
    }

    ul.navbar-nav.mega-nav .nav-item .nav-link {
        color: #000 !important;
    }

    .for-count-value {
        position: absolute;
        right: 0.6875rem;
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 50%;
        color: {{ $web_config['primary_color'] }};
        font-size: .75rem;
        font-weight: 500;
        text-align: center;
        line-height: 1.25rem;
    }

    .count-value {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 50%;
        color: {{ $web_config['primary_color'] }};
        font-size: .75rem;
        font-weight: 500;
        text-align: center;
        line-height: 1.25rem;
    }

    @media (min-width: 992px) {
        .navbar-sticky.navbar-stuck .navbar-stuck-menu.show {
            display: block;
            height: 55px !important;
        }
    }

    @media (min-width: 768px) {
        .navbar-stuck-menu {
            background-color: {{ $web_config['primary_color'] }};
            line-height: 15px;
            padding-bottom: 6px;
        }
    }

    @media (max-width: 767px) {
        .search_button {
            background-color: transparent !important;
        }
        .search_button .input-group-text i {
            color: {{$web_config['primary_color']}} !important;
        }
        .navbar-expand-md .dropdown-menu>.dropdown>.dropdown-toggle {
            position: relative;
            padding-left: 1.95rem;
        }
    }

    @media (max-width: 768px) {
        .tab-logo {
            width: 10rem;
        }

        .city-label {
            color: #838383;
        }

        .nav-line-2.nav-progressive-content {
            font-size: 14px;
            font-weight: 600
        }

        .mobile-head {
            position: fixed;
            left: 0;
            right: 0;
            top: 0;
            z-index: 6;
        }
    }

    @media (max-width: 360px) {
        .mobile-head {
            padding: 3px;
        }
    }

    @media (max-width: 471px) {
        .navbar-brand img {}

        .mega-nav1 {
            background: white;
            color: #000 !important;
            border-radius: 3px;
        }

        .mega-nav1 .nav-link {
            color: #000 !important;
        }

        .card-body.search-result-box ul li a{
            font-size: 12px;
        }
        .card-body.search-result-box ul li{
            padding: 5px;
        }
    }
</style>

<header class="box-shadow-sm rtl d-block d-md-none">

    <div class="navbar-sticky bg-light mobile-head">
        <div class="navbar navbar-expand-md navbar-light">
            <div class="container ">
                <!--    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button> -->
                <div class="d-flex pl-3">
                    {{-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                        aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button> --}}
                    <a class="navbar-brand d-sm-none {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"
                        href="{{route('home')}}">
                        <img style="height: 25px!important;" src="{{asset("storage/company")."/".$web_config['mob_logo']->value}}"
                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                        alt="{{$web_config['name']->value}}"/>
                    </a>
                </div>



                <!-- new search -->
                <div id="nav-global-location-slot" data-toggle="tooltip" data-placement="top" title="Location">
                    <span id="nav-global-location-data-modal-action" class="a-declarative nav-progressive-attribute">
                        <a id="nav-global-location-popover-link"
                            class="d-flex align-items-center nav-a nav-a-2 a-popover-trigger a-declarative nav-progressive-attribute"
                            tabindex="0">
                            <div class="mr-2 d-flex flex-column justify-content-center">
                                <span class="nav-line-2 city-label nav-progressive-content" id="auto-locations"></span>
                            </div>
                            <img class="" style="height: 30px; width: auto;"
                                src="{{asset('assets/front-end/img/loc.png')}}" alt="">
                        </a>
                    </span>
                </div>
                <!-- Toolbar-->
            </div>
        </div>
        <div class="navbar navbar-expand-md navbar-stuck-menu bg-light border-top">
            <div class="container">
                <div class="collapse navbar-collapse" id="navbarCollapse"
                    style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }}">
                    <!-- Primary menu-->
                    <ul class="navbar-nav w-100" style="{{Session::get('direction') === " rtl" ? 'padding-right: 0px'
                        : '' }}">
                        <li class="nav-item dropdown {{request()->is('/')?'active':''}}">
                            <a class="nav-link text-dark border-right py-2 mt-2" style="color: black !important"
                                href="{{route('home')}}">{{ \App\CPU\translate('Home')}}</a>
                        </li>

                        {{-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-dark border-right py-2 mt-2"
                                style="color: black !important" href="#" data-toggle="dropdown">{{
                                \App\CPU\translate('brand') }}</a>
                            <ul class="dropdown-menu scroll-bar" style="text-align: {{Session::get('direction') === "
                                rtl" ? 'right' : 'left' }};">
                                @foreach(\App\CPU\BrandManager::get_brands() as $brand)
                                <li
                                    style="border-bottom: 1px solid #e3e9ef; display:flex; justify-content:space-between; ">
                                    <div>
                                        <a class="dropdown-item"
                                            href="{{route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1])}}">
                                            {{$brand['name']}}
                                        </a>
                                    </div>
                                    <div class="align-baseline">
                                        @if($brand['brand_products_count'] > 0 )
                                        <span class="count-value px-2">( {{ $brand['brand_products_count'] }} )</span>
                                        @endif
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </li> --}}
                        @php($seller_registration=\App\Model\BusinessSetting::where(['type'=>'seller_registration'])->first()->value)
                        @if($seller_registration)
                            <li class="nav-item">
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                            style="color: black;margin-top: 5px; padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0">
                                        <a>{{ \App\CPU\translate('Seller')}}  {{ \App\CPU\translate('zone')}} </a>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                         style="min-width: 165px !important; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                        <a class="dropdown-item" href="{{route('shop.apply')}}">
                                            <b>{{ \App\CPU\translate('Become a')}} {{ \App\CPU\translate('Seller')}}</b>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{route('seller.auth.login')}}">
                                            <b>{{ \App\CPU\translate('Seller')}}  {{ \App\CPU\translate('login')}} </b>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        @if (Route::is('home'))
        <div class="bottom-nav">
            <div class="input-group-overlay p-2" style="text-align: {{Session::get('direction') === " rtl" ? 'right'
                : 'left' }}">
                <form action="{{route('products')}}" type="submit" class="search_form search-form-mobile">
                    <input class="form-control appended-form-control search-bar-input-mobile" type="text" autocomplete="off"
                        placeholder="{{\App\CPU\translate('cari_kost_dimana')}} ?" name="name">
                    <button class="input-group-append-overlay search_button mobile-search-button" type="submit">
                        <span class="input-group-text">
                            <i class="czi-search text-white"></i>
                        </span>
                    </button>
                    <input name="data_from" value="search" hidden>
                    <input name="page" value="1" hidden>
                    <diV class="card search-card"
                        style="position: absolute;background: white;z-index: 999;width: 100%;display: none">
                        <div class="card-body search-result-box"
                            style="overflow:scroll; height:400px;overflow-x: hidden"></div>
                    </diV>
                </form>
            </div>
        </div>
        @endif
    </div>
</header>


@push('script')
<script>
    fetch('https://ipapi.co/json/?key=mrRznn9f966OGRczDH9Vqg11LYzYzKTrHmGr48IDy8rTEP4xbX')
  .then(function(response) {
    return response.json();
  })
  .then(function(data) {
    // console.log('location',data);

            $('#auto-locations').append(data.city)
            $('#nav-global-location-slot').attr('data-original-title', data.country + ', ' + data.region);
  });
</script>
@endpush
