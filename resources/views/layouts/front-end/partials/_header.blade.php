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

    span.nav-line-2.nav-progressive-content {
        font-size: 14px;
        font-weight: 500;
        transition: .3s;
        color: #6e6969;
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
    ;
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 50%;

    color: {
        {
        $web_config['primary_color']
      }
    }

    ;

    font-size: .75rem;
    font-weight: 500;
    text-align: center;
    line-height: 1.25rem;
  }

  .count-value {
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 50%;

    color: {
        {
        $web_config['primary_color']
      }
    }

    ;

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
      background-color: {
          {
          $web_config['primary_color']
        }
      }

      ;
      line-height: 15px;
      padding-bottom: 6px;
    }

  }

  @media (max-width: 767px) {
    .search_button {
      background-color: transparent !important;
    }

    .search_button .input-group-text i {
      color: {
          {
          $web_config['primary_color']
        }
      }

       !important;
    }

    .navbar-expand-md .dropdown-menu>.dropdown>.dropdown-toggle {
      position: relative;

      padding- {
          {
          Session: :get('direction')==="rtl"? 'left': 'right'
        }
      }

      : 1.95rem;
    }

    .mega-nav1 {
      background: white;

      color: {
          {
          $web_config['primary_color']
        }
      }

       !important;
      border-radius: 3px;
    }

    .mega-nav1 .nav-link {
      color: {
          {
          $web_config['primary_color']
        }
      }

       !important;
    }
  }

  @media (max-width: 768px) {
    .tab-logo {
      width: 10rem;
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
  }
</style>

<header class="box-shadow-sm rtl d-none d-md-block">
  <!-- Topbar-->
  <!-- <div class="topbar">
        <div class="container ">
            <div>
                @php( $local = \App\CPU\Helpers::default_lang())
                <div
                    class="topbar-text dropdown disable-autohide {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}} text-capitalize">
                    <a class="topbar-link dropdown-toggle" href="#" data-toggle="dropdown">
                        @foreach(json_decode($language['value'],true) as $data)
                            @if($data['code']==$local)
                                <img class="{{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}" width="20"
                                     src="{{asset('public/assets/front-end')}}/img/flags/{{$data['code']}}.png"
                                     alt="Eng">
                                {{$data['name']}}
                            @endif
                        @endforeach
                    </a>
                    <ul class="dropdown-menu">
                        @foreach(json_decode($language['value'],true) as $key =>$data)
                            @if($data['status']==1)
                                <li>
                                    <a class="dropdown-item pb-1" href="{{route('lang',[$data['code']])}}">
                                        <img class="{{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"
                                             width="20"
                                             src="{{asset('public/assets/front-end')}}/img/flags/{{$data['code']}}.png"
                                             alt="{{$data['name']}}"/>
                                        <span style="text-transform: capitalize">{{\App\CPU\Helpers::get_language_name($data['code'])}}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                @php($currency_model = \App\CPU\Helpers::get_business_settings('currency_model'))
                @if($currency_model=='multi_currency')
                    <div class="topbar-text dropdown disable-autohide">
                        <a class="topbar-link dropdown-toggle" href="#" data-toggle="dropdown">
                            <span>{{session('currency_code')}} {{session('currency_symbol')}}</span>
                        </a>
                        <ul class="dropdown-menu" style="min-width: 160px!important;">
                            @foreach (\App\Model\Currency::where('status', 1)->get() as $key => $currency)
                                <li style="cursor: pointer" class="dropdown-item"
                                    onclick="currency_change('{{$currency['code']}}')">
                                    {{ $currency->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="topbar-text dropdown d-md-none {{Session::get('direction') === "rtl" ? 'mr-auto' : 'ml-auto'}}">
                <a class="topbar-link" href="tel: {{$web_config['phone']->value}}">
                    <i class="fa fa-phone"></i> {{$web_config['phone']->value}}
                </a>
            </div>
            <div class="d-none d-md-block {{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}} text-nowrap">
                <a class="topbar-link d-none d-md-inline-block" href="tel:{{$web_config['phone']->value}}">
                    <i class="fa fa-phone"></i> {{$web_config['phone']->value}}
                </a>
            </div>
        </div>
    </div> -->


  <div class="navbar-sticky bg-light mobile-head">
    <div class="navbar navbar-expand-md navbar-light">
      <div class="container ">
        <!--    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button> -->

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
          aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand d-none d-md-block {{Session::get('direction') === " rtl" ? 'ml-3' : 'mr-3' }}
          flex-shrink-0 tab-logo" href="{{route('home')}}" style="min-width: 7rem;">
          <img style="height: 45px!important;"
                         src="{{asset("storage/company")."/".$web_config['web_logo']->value}}"
                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                         alt="{{$web_config['name']->value}}"/>
        </a>
        @if (!Route::is('shop-cart'))
        <!-- Toolbar-->
        <div class="navbar-toolbar d-flex flex-shrink-0 align-items-center">
          <div class="nav-list d-flex h-100">
              <div class="navbar-tool {{Session::get('direction') === " rtl" ? 'mr-3' : 'ml-3' }}">
                <a class="nav-item-list" href="{{route('wishlists')}}">
                  <span class="nav-item">{{ \App\CPU\translate('favorite')}}</span>
                </a>
              </div>
              <div class="navbar-tool {{Session::get('direction') === " rtl" ? 'mr-3' : 'ml-3' }}">
                <a class="nav-item-list" href="{{route('account-kost')}}">
                  <span class="nav-item">{{ \App\CPU\translate('kost_saya')}}</span>
                </a>
              </div>
              <div class="navbar-tool {{Session::get('direction') === " rtl" ? 'mr-3' : 'ml-3' }}">
                <a class="nav-item-list" href="{{route('chat-with-seller')}}">
                  <span class="nav-item">{{ \App\CPU\translate('chat')}}</span>
                </a>
              </div>
              <div class="navbar-tool mr-4 ml-3">
                <div class="dropdown h-100">
                    <a class="nav-item-list" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                      <span class="nav-item">{{ \App\CPU\translate('lainnya')}}</span>
                    </a>
                    <i class="fa fa-caret-down"></i>
                    <div class="dropdown-menu" style="top:15px;" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="{{ route('account-oder') }}">Booking</a>
                        <a class="dropdown-item" href="{{ route('apply.job-order') }}">Applied</a>
                        <a class="dropdown-item" href="{{ route('wishlists') }}">Favorite</a>
                        <a class="dropdown-item" href="{{ route('chat-with-seller') }}">Chat</a>
                        <a class="dropdown-item" href="{{ route('user-account') }}">Profile</a>
                      </div>
                  </div>
                </div>
          </div>
          {{-- <div id="cart_items">
            @include('layouts.front-end.partials.cart')
          </div> --}}
          @if(auth('customer')->check())
          <div class="dropdown">
            <a class="navbar-tool ml-2 mr-2 " type="button" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
              <div class="navbar-tool-icon-box bg-secondary">
                <div class="navbar-tool-icon-box bg-secondary">
                  <img style="width: 40px;height: 40px"
                    src="{{asset('storage/profile/'.auth('customer')->user()->image)}}"
                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                    class="img-profile rounded-circle">
                </div>
              </div>
              <div class="navbar-tool-text">
                <small>Hello, {{auth('customer')->user()->f_name}}</small>
                Dashboard
              </div>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="{{route('account-oder')}}"> {{ \App\CPU\translate('Booking')}} </a>
              <a class="dropdown-item" href="{{route('user-account')}}"> {{ \App\CPU\translate('Profile')}}</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{route('customer.auth.logout')}}">{{ \App\CPU\translate('logout')}}</a>
            </div>
          </div>
          @else
          <div class="dropdown mx-3">
            <a class="navbar-tool {{Session::get('direction') === " rtl" ? 'mr-3' : 'ml-3' }}" type="button"
              data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="navbar-tool-icon-box">
                <div class="navbar-tool-icon-box">
                    <i class="fa fa-user-circle" style="font-size: 40px; line-height: 48px; color: #000;" aria-hidden="true"></i>
                </div>
              </div>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
              style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};">
              <a class="dropdown-item" href="{{route('customer.auth.login')}}">
                <i class="fa fa-sign-in {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"></i>
                {{\App\CPU\translate('Masuk_sebagai_pencari_kos')}}
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{route('seller.auth.login')}}">
                <i class="fa fa-user-circle {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2'
                  }}"></i>{{\App\CPU\translate('Masuk_sebagai_pemilik_kos')}}
              </a>
            </div>
          </div>
          @endif

          <!-- location -->
            <div id="nav-global-location-slot" data-toggle="tooltip" data-placement="top" title="Location">
                <span id="nav-global-location-data-modal-action" class="a-declarative nav-progressive-attribute">
                    <a id="nav-global-location-popover-link"
                    class="d-flex align-items-center nav-a nav-a-2 a-popover-trigger a-declarative nav-progressive-attribute"
                    tabindex="0">
                    <img class="" style="height: 25px; width: auto;" src="{{asset('assets/front-end/img/loc.png')}}" alt="">
                    <div class="ml-2 d-flex flex-column justify-content-center">
                        <span class="nav-line-2 city-label nav-progressive-content" id="auto-location"></span>
                    </div>
                </a>
                </span>
            </div>
            <!-- End location -->
        </div>
        @endif
      </div>
    </div>
  </div>
</header>

@push('script')
<script>

fetch('https://ipapi.co/json/?key=mrRznn9f966OGRczDH9Vqg11LYzYzKTrHmGr48IDy8rTEP4xbX')
  .then(function(response) {
    return response.json();
  })
  .then(function(data) {
            $('#auto-location').append(data.city)
            $('#nav-global-location-slot').attr('data-original-title', data.country + ', ' + data.region);
  });
</script>
@endpush
