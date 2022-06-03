<style>
    .owl-carousel .owl-item img {
        max-height: 155px;
    }
    .label-vendor-flash {
        position: absolute;
        background-color: {{ $web_config['primary_color'] }};
        padding: 2px 8px 2px 10px;
        top: 133px;
        right: 0;
        font-size:12px;
        color: #fff !important;
        letter-spacing: 1px;
        border-radius: 10px 0 0 0;
    }
    .label-cashback {
        position: absolute;
        background-color: #dededea3;
        padding: 2px 8px 2px 10px;
        top: 0;
        right: 0;
        font-size:12px;
        font-weight: 600;
        color: {{ $web_config['primary_color'] }} !important;
        letter-spacing: 1px;
        border-radius: 0 10px 0 10px;
    }
    .discount-hed{
        margin-top: 0;
        right: 0;
        position: absolute;
        z-index: 1;
    }
    .label-kost.flash-label {
        left: 0;
    }
    span.for-discoutn-value{
        padding: 5px 10px;
        font-size: 14px;
        font-weight: 600;
        text-transform: capitalize;
        border-radius: 0px 0px 0 15px;
    }
    .flash_deal_product {
        cursor: pointer;
        border-radius: 10px;
        min-height: 370px;
        /* max-height: 353px; */
        overflow:hidden;
        text-align: center;
    }
    .stock-out{
        z-index: 1;
    }
    .deal-product-col {
        /* margin-left: -105px */
    }
    .discount-top-f {
        z-index: 2;
        right: 0;
        font-size: 10px;
        font-weight: 700;
    }
    @media(max-width: 373px){
        .discount-hed{
            right: 7px !important;
        }
    }
    @media (max-width: 600px) {
        .label-vendor-flash{
            top: 130px
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
        .cz-countdown .cz-countdown-value{
            font-size: 14px !important;
        }
        span.for-discoutn-value{
            padding: 2px 9px;
            font-size: 12px;
            font-weight: 500;
            border-radius: 0px 0px 0 15px;
            z-index: 1;
        }
        .stock-out {
            left: 15%;
        }
        .css-12q26bf{
            background-color: #f15151;
            padding-bottom: 8px;
        }
        .css-1xpribl {
            position: relative;
            display: block;
        }
        .css-1j7i5nk {
            display: block;
            padding: 0px 4px;
            position: absolute;
            z-index: 1;
            top: 0px;
            left: -4px;
            bottom: 0px;
            min-width: 154px;
            max-width: 154px;
        }
        .css-8pf4d7 {
            position: relative;
            display: flex;
            width: 100%;
            height: 100%;
            -webkit-box-pack: center;
            justify-content: center;
            margin: 0px;
            background-color: transparent;
        }
        .css-8pf4d7 > img {
            display: block;
            object-fit: contain;
            width: 100%;
            height: 100%;
            margin: 0px auto;
        }
        .css-gfx8z3 {
    display: flex;
    position: static;
    overflow: visible;
    background-color: #fff;
    flex-flow: column nowrap;
    height: 100%;
}
    .css-vzo4av {
        position: relative;
        display: block;
        z-index: 2;
        padding: 0px 4px;
    }
        .css-1sfomcl {
    background-repeat: no-repeat;
    background-size: 99% 100%;
    height: auto;
    margin: 0px auto;
    position: relative;
    text-align: center;
    display: block;
    width: 100%;
}
.css-1sfomcl > img {
    min-height: 132px;
}
        .css-974ipl {
    position: relative;
    display: flex;
    flex: 1 1 0%;
    flex-direction: column;
    vertical-align: middle;
    height: auto;
    width: 100%;
    padding: 8px;
    overflow: hidden;
    background-color: #fff;
}
        .flash-product-title {
            font-size: 12px;
            text-transform: capitalize;
        }
        .css-974ipl .flash-product-price {
            font-weight: 700;
            font-size: 15px !important;
            text-align: center;
            color: {{$web_config['primary_color']}};
        }
        .css-13ekl7h {
            position: relative;
            z-index: 0;
            height: 100%;
            min-width: 50%;
            cursor: pointer;
            flex: 1 1 auto;
        }
        .css-13ekl7h>div {
            height: 100%;
        }
        .css-2lm59p {
            display: flex;
            flex-direction: column;
            -webkit-box-pack: justify;
            justify-content: space-between;
            height: 100%;
            box-shadow: 0 1px 6px 0 var(--N700, rgba(49, 53, 59, 0.12));
            border-radius: 9px;
            overflow: hidden;
            padding: 0px;
            margin: 0px;
        }
    }
</style>
<div class="row d-none d-md-flex" style="background-color: #f15151; padding: 15px;">
    <div class="col-md-12 col-6 d-flex align-items-center deal-product-col">
        <div class="owl-carousel owl-theme mt-2" id="flash-deal-slider">
            @if($filter == '')
            @foreach($flash_deals->products as $key=>$deal)
            @if( $deal->product)
            @php($overallRating = \App\CPU\ProductManager::get_overall_rating(isset($deal)?$deal->product->reviews:null))
            <div class="flash_deal_product rtl filtered"
              onclick="location.href='{{route('product',$deal->product->slug)}}'">
                @if($deal->product->label)
                <div class="d-flex justify-content-end for-dicount-div discount-hed">
                    <span class="for-discoutn-value">
                        {{ $deal->product->label }}
                    </span>
                </div>
                @else
                <div class="d-flex justify-content-end for-dicount-div-null">
                    <span class="for-discoutn-value-null"></span>
                </div>
                @endif
              <div class="d-flex flex-column">
                <div class="d-flex align-items-center justify-content-center div-flash">
                    <label class="label-kost flash-label text-white">{{ $deal->product->kost['name'] }}</label>
                  <img class="w-100"
                    src="{{\App\CPU\ProductManager::product_image_path('product')}}/{{json_decode($deal->product['images'])[0]}}"
                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" />

                    @if ($deal->product->added_by == 'admin')
                    <label class="label-vendor-flash capitalize">inRoom</label>
                    @endif
                </div>

                <div class="card-body inline_product text-left px-3 pt-3 clickable" style="cursor: pointer;">
                    <div class="rating-show d-flex">
                        <div class="rc-overview__label bg-c-label capitalize">{{ $deal->product->kost->penghuni }}</div>
                        @if ($deal->product->current_stock != 0)
                            @if ($deal->product->current_stock <= 3)
                            <span class="stock-label text-danger bg-c-text--label-1">
                                {{\App\CPU\translate('Sisa')}} {{ $deal->product->current_stock }} {{\App\CPU\translate('kamar')}}
                            </span>
                            @endif
                        @else
                        <span class="stock-label ml-1 text-grey bg-c-text--label-1">
                            {{\App\CPU\translate('Kamar')}} {{\App\CPU\translate('Penuh')}}
                        </span>
                        @endif
                        <div class="room-card_overview">
                            <span class="d-inline-block font-size-sm text-body">
                                    @for($inc=0;$inc<5;$inc++)
                                        @if($inc<$overallRating[0])
                                            <i class="sr-star czi-star-filled active"></i>
                                        @endif
                                        @endfor
                                        @if ($deal->product->reviews()->count() !== 0)
                                            <label class="badge-style rc-label bg-c-text--label-1" style="font-size: 10px">({{$product->reviews()->count()}})</label>
                                    @endif
                            </span>
                        </div>
                    </div>
                    <div class="kost-rc__info">
                            <div class="rc-info">
                                @php($city = strtolower($deal->product->kost['city']))
                                @php($district = strtolower($deal->product->kost['district']))
                                <span class="rc-info__name bg-c-text bg-c-text--body-4 capitalize">
                                    {{ $deal->product->kost['name'] }}
                                </span>
                                <span class="rc-info__location d-block bg-c-text bg-c-text--body-3 capitalize">
                                    {{ $city }}
                                </span>
                                <span class="rc-info__address bg-c-text bg-c-text--body-3 capitalize">
                                    {{ $deal->product->kost->note_address }}
                                </span>
                            </div>
                    </div>
                    <div class="kost-rc__facilities">
                        @php($fas = json_decode($deal->product->fasilitas_id))
                        <div class="rc-facilities">
                            <p>
                            @foreach ($fas as $f)
                                <span class="capitalize">{{ App\CPU\Helpers::fasilitas($f) }}</span>
                                <span class="rc-facilities_divider">·</span>
                                @endforeach
                            </p>
                        </div>
                    </div>
                    <div class="kost-rc__price">
                        <div class="rc-price mt-2">
                            @if($deal->product->discount > 0)
                            <div class="rc-price__additional-data">
                                <div class="price-discount">
                                        <span class="rc-price__discount-icon" aria-hidden="true">{{
                                            \App\CPU\translate('Hemat')}}</span>
                                        <span class="rc-price__additional-discount bg-c-text bg-c-text--label-1 ">
                                            @if ($deal->product->discount_type == 'percent')
                                            {{round($deal->product->discount,2)}}%
                                            @elseif($deal->product->discount_type =='flat')
                                            {{\App\CPU\Helpers::currency_converter($deal->product->discount)}}
                                            @endif
                                        </span>
                                </div>
                                <span class="rc-price__additional-discount-price bg-c-text bg-c-text--label-2 ">
                                    @if($deal->product->discount > 0)
                                        <strike style="font-size: 12px!important;color: grey!important;">
                                            {{\App\CPU\Helpers::currency_converter($deal->product->unit_price)}}
                                        </strike><br>
                                    @endif
                                </span>
                            </div>
                            @endif
                            <div class="rc-price__section"><!----> <!---->
                                <div class="rc-price__real">
                                    <span class="rc-price__text bg-c-text bg-c-text--body-1 ">
                                        {{\App\CPU\Helpers::currency_converter(
                                            $deal->product->unit_price-(\App\CPU\Helpers::get_product_discount($deal->product,$deal->product->unit_price))
                                            )}}
                                    </span>
                                    <span class="rc-price__type bg-c-text bg-c-text--body-2">
                                        / Bulan
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php($poinCount = count($poin))
                    @for ($i = 0; $i < $poinCount; $i++)
                        @if ($poin[$i]->transaction <= $deal->product->unit_price)
                            <label class="label-cashback capitalize">Cashback {{ $poin[$i]->persen }}%</label>
                        @endif
                    @endfor
                </div>

            </div>
        </div>
                @endif
                @endforeach
                @else
                @foreach($flash_deals->products as $key=>$deal)
                {{-- {{ dd($deal->product) }} --}}
                @if( $deal->product)
                @if ($deal->product->kost['city'] == $filter)
                    @php($overallRating = \App\CPU\ProductManager::get_overall_rating(isset($deal)?$deal->product->reviews:null))
                    <div class="flash_deal_product rtl filtered"
                    onclick="location.href='{{route('product',$deal->product->slug)}}'">
                        @if($deal->product->label)
                        <div class="d-flex justify-content-end for-dicount-div discount-hed">
                            <span class="for-discoutn-value">
                                {{ $deal->product->label }}
                            </span>
                        </div>
                        @else
                        <div class="d-flex justify-content-end for-dicount-div-null">
                            <span class="for-discoutn-value-null"></span>
                        </div>
                        @endif
                    <div class="d-flex flex-column">

                        <div class="d-flex align-items-center justify-content-center div-flash">
                            <label class="label-kost flash-label text-white">{{ $deal->product->kost['name'] }}</label>
                        <img class="w-100"
                            src="{{\App\CPU\ProductManager::product_image_path('product')}}/{{json_decode($deal->product['images'])[0]}}"
                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" />
                        </div>

                        <div class="card-body inline_product text-left px-3 pt-3 clickable" style="cursor: pointer;">
                            <div class="rating-show d-flex">
                                <div class="rc-overview__label bg-c-label capitalize">{{ $deal->product->kost->penghuni }}</div>
                                @if ($deal->product->current_stock <= 3)
                                <span class="stock-label text-danger bg-c-text--label-1">
                                    {{\App\CPU\translate('Sisa')}} {{ $deal->product->current_stock }} {{\App\CPU\translate('kamar')}}
                                </span>
                                @endif
                                <div class="room-card_overview">
                                    <span class="d-inline-block font-size-sm text-body">
                                            @for($inc=0;$inc<1;$inc++) @if($inc<$overallRating[0])
                                            <i class="sr-star czi-star-filled active"></i>
                                            <label class="badge-style rc-label bg-c-text--label-1"></label>{{$deal->product->reviews()->count()}}</label>
                                            @endif
                                            @endfor
                                    </span>
                                </div>
                            </div>
                            <div class="kost-rc__info">
                                    <div class="rc-info">
                                        @php($city = strtolower($deal->product->kost['city']))
                                        @php($district = strtolower($deal->product->kost['district']))
                                        <span class="rc-info__name bg-c-text bg-c-text--body-4 capitalize">
                                            {{ $deal->product->kost['name'] }}
                                        </span>
                                        <span class="rc-info__location bg-c-text bg-c-text--body-3 capitalize">
                                            {{ $city }}
                                        </span>
                                        <span class="rc-info__address bg-c-text bg-c-text--body-3 capitalize">
                                            {{ $deal->product->kost->note_address }}
                                        </span>
                                    </div>
                            </div>
                            <div class="kost-rc__facilities">
                                @php($fas = json_decode($deal->product->fasilitas_id))
                                <div class="rc-facilities">
                                    <p>
                                    @foreach ($fas as $f)
                                        <span class="capitalize">{{ App\CPU\Helpers::fasilitas($f) }}</span>
                                        <span class="rc-facilities_divider">·</span>
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                            <div class="kost-rc__price">
                                <div class="rc-price justify-content-end">
                                    @if($deal->product->discount > 0)
                                    <div class="rc-price__additional-data">
                                        <div class="price-discount">
                                                <span class="rc-price__discount-icon" aria-hidden="true">{{
                                                    \App\CPU\translate('Hemat')}}</span>
                                                <span class="rc-price__additional-discount bg-c-text bg-c-text--label-1 ">
                                                    @if ($deal->product->discount_type == 'percent')
                                                    {{round($deal->product->discount,2)}}%
                                                    @elseif($deal->product->discount_type =='flat')
                                                    {{\App\CPU\Helpers::currency_converter($deal->product->discount)}}
                                                    @endif
                                                </span>
                                        </div>
                                        <span class="rc-price__additional-discount-price bg-c-text bg-c-text--label-2 ">
                                            @if($deal->product->discount > 0)
                                                <strike style="font-size: 12px!important;color: grey!important;">
                                                    {{\App\CPU\Helpers::currency_converter($deal->product->unit_price)}}
                                                </strike><br>
                                            @endif
                                        </span>
                                    </div>
                                    @endif
                                    <div class="rc-price__section"><!----> <!---->
                                        <div class="rc-price__real">
                                            <span class="rc-price__text bg-c-text bg-c-text--body-1 ">
                                                {{\App\CPU\Helpers::currency_converter(
                                                    $deal->product->unit_price-(\App\CPU\Helpers::get_product_discount($deal->product,$deal->product->unit_price))
                                                    )}}
                                            </span>
                                            <span class="rc-price__type bg-c-text bg-c-text--body-2">
                                                / Bulan
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    </div>
                    @else
                    {{-- <div class="not_found d-none">no</div> --}}
                @endif
                {{-- @else
                <div class="text-center">
                    <span class="badge badge-warning">Promo dilokasi ini tidak tersedia</span>
                </div> --}}
            @endif


            @endforeach
            @endif
        </div>


    </div>
    </div>

{{-- mobile --}}
<div class="css-1xpribl d-block d-md-none">
    <div class="css-12q26bf row" data-testid="tblHomeCarouselProducts">
        <div class="col-12">
            <div class="owl-carousel owl-theme mt-2" id="flash-deal-slider-mobile">
                @foreach($flash_deals->products as $key=>$deal)
                    @if( $deal->product)
                    @php($overallRating =
                    \App\CPU\ProductManager::get_overall_rating(isset($deal)?$deal->product->reviews:null))
                <div class="css-vzo4av" onclick="location.href='{{route('product',$deal->product->slug)}}'">
                    <div class="css-13ekl7h" data-testid="master-product-card">
                        <div class="css-2lm59p" data-testid="">
                            <div class="pcv3__container css-gfx8z3">
                                <div class="css-zimbi">
                                    @if($deal->product->label)
                                    <div class="d-flex justify-content-end for-dicount-div discount-hed" style="right: 0;position: absolute; z-index: 1;">
                                        <span class="for-discoutn-value">
                                            {{ $deal->product->label }}
                                        </span>
                                    </div>
                                    @else
                                    <div class="d-flex justify-content-end for-dicount-div-null">
                                        <span class="for-discoutn-value-null"></span>
                                    </div>
                                    @endif
                                    <div class="css-1sfomcl" data-testid="imgProduct">
                                        <label class="label-kost flash-label text-white">{{ $deal->product->kost['name'] }}</label>
                                        <img crossorigin="anonymous" src="{{\App\CPU\ProductManager::product_image_path('product')}}/{{json_decode($deal->product['images'])[0]}}"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" title="" alt="">
                                        @if ($deal->product->added_by == 'admin')
                                        <label class="label-vendor-flash capitalize">inRoom</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="css-974ipl">
                                    <div class="kost-rc__overview">
                                        <div class="rc-overview">
                                            <div class="capitalize rc-overview__label bg-c-label bg-c-label--rainbow bg-c-label--rainbow-white">
                                                {{ $deal->product->kost->penghuni }}
                                            </div>
                                            @if ($deal->product->current_stock != 0)
                                                @if ($deal->product->current_stock <= 3)
                                                <span class="stock-label text-danger bg-c-text--label-1">
                                                    {{\App\CPU\translate('Sisa')}} {{ $deal->product->current_stock }} {{\App\CPU\translate('kamar')}}
                                                </span>
                                            @endif
                                            @else
                                            <span class="stock-label ml-1 text-grey bg-c-text--label-1">
                                               {{\App\CPU\translate('Penuh')}}
                                            </span>
                                            @endif

                                            <span class="rc-overview__availability bg-c-text bg-c-text--label-4 bg-c-text--italic ">
                                                @for($inc=0;$inc<1;$inc++) @if($inc<$overallRating[0])
                                                <i class="sr-star czi-star-filled active"></i>
                                                <label class="badge-style rc-label bg-c-text--label-1"></label>{{$deal->product->reviews()->count()}}</label>
                                                @endif
                                                @endfor
                                            </span>
                                        </div>
                                    </div>
                                    <div class="kost-rc__info">
                                        <div class="rc-info">
                                            @php($city = strtolower($deal->product->kost['city']))
                                            @php($district = strtolower($deal->product->kost['district']))
                                            <span class="rc-info__name bg-c-text bg-c-text--body-4 capitalize">
                                                {{ $deal->product->kost['name'] }}
                                            </span>
                                            <span class="rc-info__location d-block bg-c-text bg-c-text--body-3 capitalize">
                                                {{ $city }}
                                            </span>
                                            <span class="rc-info__address bg-c-text bg-c-text--body-3 capitalize">
                                                {{ $deal->product->kost->note_address }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="kost-rc__facilities">
                                        @php($fas = json_decode($deal->product->fasilitas_id))
                                        @if (count($fas) > 0)
                                        <div class="rc-facilities">
                                            <p>
                                            @foreach ($fas as $f)
                                                <span class="capitalize">{{ App\CPU\Helpers::fasilitas($f) }}</span>
                                                <span class="rc-facilities_divider">·</span>
                                                @endforeach
                                            </p>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="kost-rc__price pb-2">
                                        <div class="rc-price justify-content-end">
                                            <div class="rc-price__additional-data">
                                                <div class="price-discount">
                                                    <span class="rc-price__discount-icon" aria-hidden="true">{{
                                                        \App\CPU\translate('Hemat')}}</span>
                                                    <span class="rc-price__additional-discount bg-c-text bg-c-text--label-1 ">
                                                        @if ($deal->product->discount_type == 'percent')
                                                        {{round($deal->product->discount,2)}}%
                                                        @elseif($deal->product->discount_type =='flat')
                                                        {{\App\CPU\Helpers::currency_converter($deal->product->discount)}}
                                                        @endif
                                                    </span>
                                                </div>
                                                <span class="rc-price__additional-discount-price bg-c-text bg-c-text--label-4 ">
                                                    @if($deal->product->discount > 0)
                                                        <strike style="font-size: 12px!important;color: grey!important;">
                                                            {{\App\CPU\Helpers::currency_converter($deal->product->unit_price)}}
                                                        </strike><br>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="rc-price__section">
                                                <div class="rc-price__real">
                                                    <span class="rc-price__text bg-c-text bg-c-text--label-1 ">
                                                        {{\App\CPU\Helpers::currency_converter(
                                                            $deal->product->unit_price-(\App\CPU\Helpers::get_product_discount($deal->product,$deal->product->unit_price))
                                                            )}}
                                                    </span>
                                                    <span class="rc-price__type bg-c-text bg-c-text--label-2 ">
                                                        / bulan
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $(document).ready(function(){
        var found = $('.filtered').length;
        if(found == 0){
                $('#flash-card').addClass('d-none')
                // $('#not_found').removeClass('d-none')
                $('#not_found').addClass('d-flex')
        }
        var maxHeight = 0;

        $('.flash_deal_product').each(function(){
        var thisH = $(this).height();
        if (thisH > maxHeight) { maxHeight = thisH; }
        });

        $('.flash_deal_product').height(maxHeight);

        var mH = 0;

        $('.css-vzo4av').each(function(){
        var thisH = $(this).height();
        if (thisH > maxHeight) { maxHeight = thisH; }
        });

        $('.css-vzo4av').height(maxHeight);
    })
</script>
@endpush
