@php($overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews))
<style>
    .card-header {
        position: relative;
    }
    .img-box.single {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        right: 0;
        border-radius: 10px 10px 0 0;
    }
    .label-kost.single-label{
        left: 0;
        background-color: #24b400;
    }
    .product-card.card.single-card{
        border-radius: 10px;
        overflow: hidden;
    }
</style>
<div class="product-card single-card card {{$product['current_stock']==0?'stock-card':''}}"
    style="margin-bottom: 10px; box-shadow: none;">
    <label class="label-kost single-label text-white">{{ $product->kost['name'] }}</label>

        <div class="card-header single-product inline_product clickable p-0 pb-1">
            <div class="img-box single">
                <a href="{{route('product',$product->slug)}}">
                    <img src="{{\App\CPU\ProductManager::product_image_path('product')}}/{{json_decode($product['images'])[0]}}"
                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                        class="h-100"
                        style="width: 100%;max-height: 220px">
                </a>
            </div>
        </div>
        @if ($product->added_by == 'admin')
        <label class="label-vendor capitalize">inRoom</label>
        @endif

        <div class="card-body d-flex flex-column justify-content-between single-product inline_product text-left px-3 pt-2 clickable"
            style="cursor: pointer;">
            <div class="rating-show d-flex">
                <div class="rc-overview__label bg-c-label capitalize">{{ $product->kost->penghuni }}</div>
                @if ($product->current_stock <= 3 && $product->current_stock != 0 && $product->current_stock != 'null' )
                <span class="stock-label ml-1 text-danger bg-c-text--label-1">
                    {{\App\CPU\translate('Sisa')}} {{ $product->current_stock }} {{\App\CPU\translate('kamar')}}
                </span>
                @elseif($product->current_stock == 0)
                <span class="stock-label ml-1 text-grey bg-c-text--label-1">
                    {{\App\CPU\translate('Kamar')}} {{\App\CPU\translate('Penuh')}}
                </span>
                @endif
                <div class="room-card_overview">
                    <span class="d-inline-block font-size-sm text-body">
                            @for($inc=0;$inc<1;$inc++) @if($inc<$overallRating[0])
                            <i class="sr-star czi-star-filled active"></i>
                            <label class="badge-style rc-label bg-c-text--label-1"></label>{{$product->reviews()->count()}}</label>
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
                <div class="rc-facilities">
                    <p class="lined mb-0">
                    @foreach ($fas as $f)
                        <span class="capitalize">{{ App\CPU\Helpers::fasilitas($f) }}</span>
                        <span class="rc-facilities_divider">·</span>
                        @endforeach
                    </p>
                </div>
            </div>
            @endif

            <div class="kost-rc__price">
                <div class="rc-price justify-content-end">
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
        </div>
</div>
