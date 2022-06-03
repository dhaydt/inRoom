@php
    $overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews);
    $rating = \App\CPU\ProductManager::get_rating($product->reviews);
    $star = \App\CPU\ProductManager::averageStar($rating);
@endphp
<div class="product-card card {{$product['current_stock']==0?'stock-card':''}}"
    style="margin-bottom: 10px; box-shadow: none;">
    <label class="label-kost text-white">{{ $product->kost['name'] }}</label>

        <div class="card-header inline_product clickable p-0 pb-1" style="cursor: pointer; height:  168px; ">
            {{-- @if($product->discount > 0)
            <div class="d-flex justify-content-end for-dicount-div discount-hed" style="right: 0;position: absolute">
                <span class="for-discoutn-value">
                    @if ($product->discount_type == 'percent')
                    {{round($product->discount,2)}}%
                    @elseif($product->discount_type =='flat')
                    {{\App\CPU\Helpers::currency_converter($product->discount)}}
                    @endif
                    OFF
                </span>
            </div>
            @else
            <div class="d-flex justify-content-end for-dicount-div-null">
                <span class="for-discoutn-value-null"></span>
            </div>
            @endif --}}
            <div class="d-flex align-items-center justify-content-center d-block img-box">
                <a href="{{route('product',$product->slug)}}">
                    <img src="{{\App\CPU\ProductManager::product_image_path('product')}}/{{json_decode($product['images'])[0]}}"
                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                        class="h-100"
                        style="width: 100%;max-height: 220px">
                </a>
            </div>
        </div>

        <div class="card-body inline_product text-left p-1 clickable"
            style="cursor: pointer;">
            <div class="rating-show d-flex">
                <div class="rc-overview__label bg-c-label capitalize">{{ $product->kost->penghuni }}</div>
                        @if ($product->current_stock <= 3)
                        <span class="stock-label ml-1 text-danger bg-c-text--label-1">
                            {{\App\CPU\translate('Sisa')}} {{ $product->current_stock }} {{\App\CPU\translate('kamar')}}
                        </span>
                        @endif
                <div class="room-card_overview">
                    <span class="d-inline-block font-size-sm text-body">
                        @for($inc=0;$inc<1;$inc++)
                        @if($inc<$overallRating[0])
                            <i class="sr-star czi-star-filled active"></i>
                            <label class="badge-style rc-label bg-c-text--label-1">{{$star}}</label>
                        @endif
                        @endfor
                    </span>
                </div>
            </div>
            <div class="kost-rc__info">
                <a href="{{route('product',$product->slug)}}">
                    <div class="rc-info">
                        @php($city = strtolower($product->kost['city']))
                        @php($district = strtolower($product->kost['district']))
                        <span class="rc-info__name bg-c-text bg-c-text--body-4 capitalize">
                            {{ $product->kost['name'] }} {{ $product->type }} {{ $city }}
                        </span>
                        <span class="rc-info__location bg-c-text bg-c-text--body-3 capitalize">
                            {{ $district }}
                        </span>
                    </div>
                </a>
            </div>
            @php($fas = json_decode($product->fasilitas_id))
            @if (count($fas) > 0)
            <div class="kost-rc__facilities">
                <div class="rc-facilities">
                    @foreach ($fas as $f)
                    <span>
                        <span class="capitalize">{{ App\CPU\Helpers::fasilitas($f) }}</span>
                        <span class="rc-facilities_divider">·</span>
                    </span>
                    @endforeach

                </div>
            </div>
            @endif

            <div class="kost-rc__price">
                <div class="rc-price">
                    @if($product->discount > 0)
                    <div class="rc-price__additional-data">
                        <div class="price-discount">
                            <span class="rc-price__discount-icon" aria-hidden="true">{{
                                \App\CPU\translate('Hemat')}}</span>
                            <span class="rc-price__additional-discount bg-c-text bg-c-text--label-1 ">
                                @if ($product->discount_type == 'percent')
                                {{round($product->discount,2)}}%
                                @elseif($product->discount_type =='flat')
                                {{\App\CPU\Helpers::currency_converter($product->discount)}}
                                @endif
                            </span>
                        </div>
                        <span class="rc-price__additional-discount-price bg-c-text bg-c-text--label-2 ">
                            @if($product->discount > 0)
                                <strike style="font-size: 12px!important;color: grey!important;">
                                    {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                                </strike><br>
                            @endif
                        </span>
                    </div>
                    @endif
                    <div class="rc-price__section">
                        <div class="rc-price__real">
                            <span class="rc-price__text bg-c-text bg-c-text--body-1 ">
                                {{\App\CPU\Helpers::currency_converter(
                                    $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))
                                    )}}
                            </span>
                            <span class="rc-price__type bg-c-text bg-c-text--body-2 ">
                                / Bulan
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="justify-content-between text-center">
                <div class="product-price text-center">
                    @if($product->discount > 0)
                    <strike style="font-size: 12px!important;color: grey!important;">
                        {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                    </strike><br>
                    @endif
                    <span class="text-accent">
                        {{\App\CPU\Helpers::currency_converter(
                        $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))
                        )}}
                    </span>
                </div>
            </div> --}}

        </div>
        {{-- <div class="d-flex justify-content-start w-100" style="position: absolute;bottom: 0px;left: 5px;z-index: 1;">
            <div class="flag">
                <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}" width="20"
                    src="{{asset('public/assets/front-end')}}/img/flags/{{ strtolower($product->country)  }}.png"
                    alt="Eng">
            </div>
            @php($c_name = App\Country::where('country', $product->country)->get())
            <span style="font-size: 13px; color: #616166; line-height: 1.6;">{{ $c_name[0]->country_name }}</span>
        </div> --}}

        {{-- <div class="card-body card-body-hidden" style="padding-bottom: 5px!important;">
            <div class="text-center">
                @if(Request::is('product/*'))
                <a class="btn btn-primary btn-sm btn-block mb-2" href="{{route('product',$product->slug)}}"
                    style="padding: 0.425rem 0.3rem; font-size: .7125rem;">
                    <i class="czi-forward align-middle {{Session::get('direction') === " rtl" ? 'ml-1' : 'mr-1' }}"></i>
                    {{\App\CPU\translate('View')}}
                </a>
                @else
                <a class="btn btn-primary btn-sm btn-block mb-2" href="javascript:"
                    onclick="quickView('{{$product->id}}')" style="padding: 0.425rem 0.3rem; font-size: .7125rem;">
                    <i class="czi-eye align-middle {{Session::get('direction') === " rtl" ? 'ml-1' : 'mr-1' }}"></i>
                    {{\App\CPU\translate('Quick')}} {{\App\CPU\translate('View')}}
                </a>
                @endif
            </div>
        </div> --}}
</div>
