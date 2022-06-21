@php
    $overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews);
    $rating = \App\CPU\ProductManager::get_rating($product->reviews);
    $star = \App\CPU\ProductManager::averageStar($rating);
@endphp
<style>
.ell{
    /* width: 500px !important; */
}
.ell p {
    text-overflow:ellipsis;
    overflow:hidden;
    display: -webkit-box !important;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    white-space: normal;
    font-size: 12px;
}
.search-card{
    display: flex;
    flex-direction: row;
    height: 200px;
    background-color: #f2f3f7;
}
.img-box-search{
    background: #999;
    border-radius: 8px;
    display: flex;
    height: 100%;
    overflow: hidden;
    position: relative;
    width: 300px;
    height: 200px;
}
.img-box-search img{
    height: 100%;
    width: 100%;
}
.product-card .card-body.inline_product_search{
    background-color: #f2f3f7;
}
.rc-price__additional-data{
    justify-content: start;
}
label.label-vendor {
    top: 179px;
    border-radius: 10px 0 8px 0;
}
@media(max-width: 600px){
    .ell p {
        -webkit-line-clamp: 2;
        font-size: 10px;
    }
    .rc-price__additional-data{
        justify-content: start;
    }
}
</style>
<div class="product-card search-card card {{$product['current_stock']==0?'stock-card':''}}"
    style="margin-bottom: 10px; box-shadow: none;">
    <label class="label-kost text-white" style="background-color: #24b400;">{{ $product->kost['name'] }}</label>

        <div class="card-header inline_product clickable p-0 pb-1" style="cursor: pointer; position:relative; overflow:hidden;">
            <div class="d-flex align-items-center justify-content-center d-block img-box-search">
                <a href="{{route('product',$product->slug)}}" class="h-100 w-100">
                    <img src="{{\App\CPU\ProductManager::product_image_path('product')}}/{{json_decode($product['images'])[0]}}"
                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'">
                </a>
            </div>
            @if ($product->added_by == 'admin')
            <label class="label-vendor capitalize">inRoom</label>
            @endif
        </div>

        <div class="card-body d-flex flex-column justify-content-between inline_product_search text-left ml-3 p-0 clickable"
            style="cursor: pointer; max-width: 65%;">
            <div class="rating-show d-flex">
                <div class="rc-overview__label bg-c-label capitalize">{{ $product->kost->penghuni }}</div>
                        @if ($product->current_stock <= 3 && $product->current_stock !== 0)
                        <span class="stock-label ml-1 text-danger bg-c-text--label-1">
                            {{\App\CPU\translate('Sisa')}} {{ $product->current_stock }} {{\App\CPU\translate('kamar')}}
                        </span>
                        @endif
                        @if ($product->current_stock == 0)
                        <span class="stock-label ml-1 text-danger bg-c-text--label-1">
                            {{\App\CPU\translate('Kamar_penuh')}}
                        </span>
                        @endif
            </div>
            <div class="kost-rc__info">
                <a href="{{route('product',$product->slug)}}">
                    <div class="rc-info">
                        @php($city = strtolower($product->kost['city']))
                        @php($district = strtolower($product->kost['district']))
                        <span class="rc-info__name bg-c-text bg-c-text--body-4 capitalize">
                            {{ $product->kost['name'] }}
                        </span>
                        <span class="rc-info__location bg-c-text bg-c-text--body-3 d-block capitalize">
                            {{ $city }}
                        </span>
                        <span class="rc-info__address bg-c-text bg-c-text--body-3 capitalize">
                            {{ $product->kost->note_address }}
                        </span>
                    </div>
                </a>
            </div>
            @php($fas = json_decode($product->fasilitas_id))
            @if (count($fas) > 0)
            <div class="kost-rc__facilities">
                <div class="ell">
                    <p class="mb-0">
                        @foreach ($fas as $f)
                        <span>
                            <span class="capitalize">{{ App\CPU\Helpers::fasilitas($f) }}</span>
                            <span class="rc-facilities_divider">·</span>
                        </span>
                        @endforeach
                    </p>

                </div>
            </div>
            @endif
            <div class="price_landscape d-flex">
                <div class="kost-rc__price h-100">
                    <div class="rc-price mt-auto">
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
                            <div class="rc-price__real d-flex d-md-none">
                                <span class="rc-price__text bg-c-text bg-c-text--body-1 ">
                                    {{\App\CPU\Helpers::currency_converter(
                                        $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))
                                        )}}
                                </span>
                                <span class="rc-price__type bg-c-text bg-c-text--body-2 ">
                                    / Bulan
                                </span>
                            </div>
                            <div class="rc-price__real d-none d-md-flex">
                                <span class="rc-price__text bg-c-text bg-c-text--body-1 ">
                                    {{\App\CPU\Helpers::currency_converter(
                                        $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))
                                        )}}
                                </span>
                                <span class="rc-price__type bg-c-text bg-c-text--body-2 ">
                                    / Bulan
                                </span>
                            </div>
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
                    </div>
                </div>
            </div>
        </div>
</div>
