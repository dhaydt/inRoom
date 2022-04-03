<style>

    body {
        font-family: 'Titillium Web', sans-serif
    }

    .card {
        border: none
    }

    .rc-info .rc-info__location.bg-c-text{
            color: #959595;
    }

    .totals tr td {
        font-size: 13px
    }

    .footer span {
        font-size: 12px
    }

    .product-qty span {
        font-size: 12px;
        color: #6A6A6A;
    }

    .font-name {
        font-weight: 600;
        font-size: 15px;
        color: #030303;
    }

    .sellerName {

        font-weight: 600;
        font-size: 14px;
        color: #030303;
    }

    .wishlist_product_img img {
        margin: 15px;
    }

    @media (max-width: 600px) {
        .font-name {
            font-size: 12px;
            font-weight: 400;
        }

        .amount {
            font-size: 12px;
        }
    }

    @media (max-width: 600px) {
        .wishlist_product_img {
            width: 20%;
        }

        .forPadding {
            padding: 6px;
        }

        .sellerName {

            font-weight: 400;
            font-size: 12px;
            color: #030303;
        }

        .wishlist_product_desc {
            width: 50%;
            margin-top: 0px !important;
        }

        .wishlist_product_icon {
            margin-left: 1px !important;
        }

        .wishlist_product_btn {
            width: 30%;
            margin-top: 10px !important;
        }

        .wishlist_product_img img {
            margin: 8px;
        }
    }
</style>
@if($wishlists->count()>0)
{{-- {{ dd($wishlists) }} --}}
    @foreach($wishlists as $wishlist)
        @php($product = $wishlist->product)
        @if( $wishlist->product)
            <div class="card box-shadow-sm mt-2">
                <div class="product mb-2">
                    <div class="card">
                        <div class="row forPadding">
                            <div class="wishlist_product_img col-md-2 col-lg-2 col-sm-2">
                                <div href="{{route('product',$product->slug)}}">
                                    <img
                                        src="{{\App\CPU\ProductManager::product_image_path('product')}}/{{json_decode($product['images'])[0]}}"
                                        width="100">
                                </div>
                            </div>
                            <div class="wishlist_product_desc col-md-8 mt-1">
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
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="rating-show d-flex">
                                        <div class="rc-overview__label bg-c-label capitalize">{{ $product->kost->penghuni }}</div>
                                            @if ($product->current_stock <= 3)
                                            <span class="stock-label ml-1 text-danger bg-c-text--label-1">
                                                {{\App\CPU\translate('Sisa')}} {{ $product->current_stock }} {{\App\CPU\translate('kamar')}}
                                            </span>
                                            @endif
                                    </div>
                                    @php($tax = ($product->tax_type == 'percent' ? $product->unit_price + ($product->unit_price * $product->tax) / 100 : $product->unit_price + $product->tax))
                                    <div class="">
                                    <span
                                        class="mt-auto font-weight-bold amount">{{App\CPU\Helpers::currency_converter($tax)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="wishlist_product_btn col-md-2 col-sm-6 mt-3 float-right bodytr font-weight-bold"
                                style="color: #92C6FF;">

                                <a href="javascript:" class="wishlist_product_icon ml-2 pull-right mr-3">
                                    <i class="czi-close-circle" onclick="removeWishlist('{{$product['id']}}')"
                                       style="color: red"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <span class="badge badge-danger">{{\App\CPU\translate('item_removed')}}</span>
        @endif
    @endforeach
@else
    <center>
        <h6 class="text-muted">
            {{\App\CPU\translate('data_not_found')}}.
        </h6>
    </center>
@endif
