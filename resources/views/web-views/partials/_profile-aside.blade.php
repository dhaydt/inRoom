<style>
    body {
        font-family: 'Titillium Web', sans-serif
    }
    .footer span {
        font-size: 12px
    }
    .product-qty span {
        font-size: 12px;
        color: #6A6A6A;
    }
    label {
        font-size: 16px;
    }
    .divider-role {
        border-bottom: 1px solid whitesmoke;
    }
    .sidebarL h3:hover + .divider-role {
        border-bottom: 3px solid {{$web_config['secondary_color']}}    !important;
        transition: .2s ease-in-out;
    }
    .price_sidebar {
        padding: 20px;
    }

    @media (max-width: 600px) {
        .sidebar_heading h1 {
            text-align: center;
            color: aliceblue;
            padding-bottom: 17px;
            font-size: 19px;
        }
        .sidebarR {
            padding: 24px;
        }
        .price_sidebar {
            padding: 20px;
        }
    }
    @media(max-width: 500px){
        .footer-booking-nav{
            position: fixed;
            left: 0;
            bottom: 0;
            right: 0;
            background-color: #fff;
            z-index: 2;
        }
        .book-icon{
            height: 25px;
        }
        .item-nav{
            font-size: 12px;
            text-transform: capitalize;
        }
    }

</style>

<div class="sidebarR col-lg-3 col-md-3 d-none d-md-block">
    <!--Price Sidebar-->
    <div class="price_sidebar rounded-lg box-shadow-sm" id="shop-sidebar" style="margin-bottom: -10px;background: white">
        <div class="box-shadow-sm">

        </div>
        <div class="pb-0" style="padding-top: 12px;">
            <!-- Filter by price-->
            <div class="sidebarL">
                <h3 class="widget-title btnF" style="font-weight: 700;">
                    <a class="{{Request::is('account-kost*') ? 'active-menu' :''}}" href="{{route('account-kost') }} ">{{\App\CPU\translate('Kost')}}</a>
                </h3>
                <div class="divider-role"
                     style="border: 1px solid whitesmoke; margin-bottom: 14px;  margin-top: -6px;">
                </div>
            </div>
        </div>
        <div class="pb-0" style="padding-top: 12px;">
            <!-- Filter by price-->
            <div class="sidebarL">
                <h3 class="widget-title btnF" style="font-weight: 700;">
                    <a class="{{Request::is('account-oder*') || Request::is('account-order-details*') ? 'active-menu' :''}}" href="{{route('account-oder') }} ">{{\App\CPU\translate('booking')}}</a>
                </h3>
                <div class="divider-role"
                     style="border: 1px solid whitesmoke; margin-bottom: 14px;  margin-top: -6px;">
                </div>
            </div>
        </div>

        <div class="pb-0" style="padding-top: 12px;">
            <!-- Filter by price-->
            <div class="sidebarL">
                <h3 class="widget-title btnF" style="font-weight: 700;">
                    <a class="{{Request::is('job-order*') ? 'active-menu' :''}}" href="{{route('apply.job-order') }} ">{{\App\CPU\translate('Job_vacancy')}}</a>
                </h3>
                <div class="divider-role"
                     style="border: 1px solid whitesmoke; margin-bottom: 14px;  margin-top: -6px;">
                </div>
            </div>
        </div>
        <div class="pb-0">
            <!-- Filter by price-->
            <div class="sidebarL">
                <h3 class="widget-title btnF " style="font-weight: 700;">
                    <a class="{{Request::is('wishlists*') ? 'active-menu':''}}" href="{{route('wishlists')}}"> {{\App\CPU\translate('favorites')}}  </a></h3>
                <div class="divider-role"
                     style="border: 1px solid whitesmoke; margin-bottom: 14px;  margin-top: -6px;">
                </div>
            </div>
        </div>

        {{--to do--}}
        <div class="pb-0">
            <!-- Filter by price-->
            <div class="sidebarL">
                <h3 class="widget-title btnF" style="font-weight: 700;">
                    <a class="{{Request::is('chat*')?'active-menu':''}}" href="{{route('chat-with-seller')}}">{{\App\CPU\translate('chat_with_seller')}}</a>
                </h3>
                <div class="divider-role"
                     style="border: 1px solid whitesmoke; margin-bottom: 14px;  margin-top: -6px;">
                </div>
            </div>
        </div>

        <div class="pb-0">
            <!-- Filter by price-->
            <div class=" sidebarL">
                <h3 class="widget-title btnF" style="font-weight: 700;">
                    <a class="{{Request::is('user-account*')?'active-menu':''}}" href="{{route('user-account')}}">
                        {{\App\CPU\translate('profile_info')}}
                    </a>
                </h3>
                <div class="divider-role"
                     style="border: 1px solid whitesmoke; margin-bottom: 14px;  margin-top: -6px;">
                </div>
            </div>
        </div>
        {{-- <div class="pb-0">
            <div class=" sidebarL">
                <h3 class="widget-title btnF" style="font-weight: 700;">
                    <a class="{{Request::is('account-address*')?'active-menu':''}}"
                       href="{{ route('account-address') }}">{{\App\CPU\translate('address')}} </a>
                </h3>
                <div class="divider-role"
                     style="border: 1px solid whitesmoke; margin-bottom: 14px;  margin-top: -6px;">
                </div>
            </div>
        </div> --}}
        <div class="pb-0">
            <!-- Filter by price-->
            <div class=" sidebarL">
                <h3 class="widget-title btnF" style="font-weight: 700;">
                    <a class="{{(Request::is('account-ticket*') || Request::is('support-ticket*'))?'active-menu':''}}"
                       href="{{ route('account-tickets') }}">{{\App\CPU\translate('support_ticket')}}</a></h3>
                <div class="divider-role"
                     style="border: 1px solid whitesmoke; margin-bottom: 14px;  margin-top: -6px;">
                </div>
            </div>
        </div>
        <div class="pb-0">
            <!-- Filter by price-->
            <div class=" sidebarL">
                <h3 class="widget-title btnF" style="font-weight: 700;">
                    <a class="{{(Request::is('account-verify*') || Request::is('account-verify*'))?'active-menu':''}}"
                       href="{{ route('account-verify') }}">{{\App\CPU\translate('account_verification')}}</a></h3>
                <div class="divider-role"
                     style="border: 1px solid whitesmoke; margin-bottom: 14px;  margin-top: -6px;">
                </div>
            </div>
        </div>
        {{--<div class="pb-0" style="padding-top: 12px;">
            <!-- Filter by price-->
            <div class="sidebarL ">
                <h3 class="widget-title btnF" style="font-weight: 700;">
                    <a class="{{Request::is('account-transaction*')?'active-menu':''}}"
                       href="{{ route('account-transaction') }}">
                       {{\App\CPU\translate('tansction_history')}}
                    </a>
                </h3>
                <div class="divider-role"
                     style="border: 1px solid whitesmoke; margin-bottom: 14px;  margin-top: -6px;"></div>
            </div>
        </div>--}}
    </div>
</div>
<div class="footer-booking-nav d-flex d-md-none px-2 pb-1 pt-2">
<div class="container">
    <div class="row">
        <div class="col-2 d-flex flex-column align-items-center">
            <a class="{{Request::is('account-kost*') ? 'active-menu' :''}} d-flex flex-column align-items-center" href="{{route('account-kost') }} ">
                <div class="book-icon-frame">
                    <img src="{{ asset('assets/front-end/img/home.png') }}" alt="" class="book-icon">
                </div>
                <span class="item-nav">
                    {{\App\CPU\translate('Kost')}}
                </span>
            </a>
        </div>
        <div class="col-2 d-flex flex-column align-items-center">
            <a class="{{Request::is('account-oder*') || Request::is('account-order-details*') ? 'active-menu' :''}} d-flex flex-column align-items-center" href="{{route('account-oder') }} ">
                <div class="book-icon-frame">
                    <img src="{{ asset('assets/front-end/img/booking.png') }}" alt="" class="book-icon">
                </div>
                <span class="item-nav">
                    {{\App\CPU\translate('booking')}}
                </span>
            </a>
        </div>
        <div class="col-2 d-flex flex-column align-items-center">
            <a class="{{ Request::is('apply.job-order*')  ? 'active-menu' :''}} d-flex flex-column align-items-center" href="{{route('apply.job-order') }} ">
                <div class="book-icon-frame">
                    <img src="{{ asset('assets/front-end/img/apply.png') }}" alt="" class="book-icon">
                </div>
                <span class="item-nav">
                    {{\App\CPU\translate('Applied')}}
                </span>
            </a>
        </div>
        <div class="col-2 d-flex flex-column align-items-center">
            <a class="{{Request::is('wishlists*')?'active-menu':''}} d-flex flex-column align-items-center" href="{{route('wishlists')}}">
            <div class="book-icon-frame">
                <img src="{{ asset('assets/front-end/img/fav.png') }}" alt="" class="book-icon">
            </div>
            <span class="item-nav">
                {{\App\CPU\translate('favorite')}}
            </span>
            </a>
        </div>
        <div class="col-2 d-flex flex-column align-items-center">
            <a class="{{Request::is('chat*')?'active-menu':''}} d-flex flex-column align-items-center" href="{{route('chat-with-seller')}}">
                <div class="book-icon-frame">
                    <img src="{{ asset('assets/front-end/img/chat.png') }}" alt="" class="book-icon">
                </div>
                <span class="item-nav">
                    {{\App\CPU\translate('Chat')}}
                </span>
            </a>
        </div>
        <div class="col-2 d-flex flex-column align-items-center">
            <a type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-transform: capitalize">
                <div class="book-icon-frame">
                    <img src="{{ asset('assets/front-end/img/other.png') }}" alt="" class="book-icon">
                </div>
                <span class="item-nav">
                    others
                </span>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{route('user-account')}}"> {{ \App\CPU\translate('profile')}}</a>
                {{-- <div class="dropdown-divider"></div> --}}
                <a class="dropdown-item" href="{{route('account-tickets')}}"> {{ \App\CPU\translate('support_ticket')}} </a>
                <a class="dropdown-item" href="{{route('account-verify')}}">{{ \App\CPU\translate('account_verification')}}</a>
            </div>
        </div>

    </div>
</div>
</div>


















