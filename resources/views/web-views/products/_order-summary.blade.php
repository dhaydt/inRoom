<style>
    .cart_title {
        font-weight: 400 !important;
        font-size: 16px;
    }

    .cart_value {
        font-weight: 600 !important;
        font-size: 16px;
    }

    .cart_total_value {
        font-weight: 700 !important;
        font-size: 25px !important;
        /* color: {{$web_config['primary_color']}}     !important; */
    }
</style>

<aside class="col-md-12 pt-4 pt-lg-0">
    <div class="cart_total">
        @php($sub_total=0)
        @php($total_tax=0)
        @php($total_shipping_cost=0)
        @php($total_discount_on_product=0)
        @php($cart=\App\CPU\CartManager::get_cart())
        @php($shipping_cost=\App\CPU\CartManager::get_shipping_cost())
        @if($cart->count() > 0)
            @foreach($cart as $key => $cartItem)
                @php($sub_total+=$cartItem['price']*$cartItem['quantity'])
                @php($total_tax+=$cartItem['tax']*$cartItem['quantity'])
                @php($total_discount_on_product+=$cartItem['discount']*$cartItem['quantity'])
            @endforeach
            {{-- @php($total_shipping_cost=$shipping_cost) --}}
        @else
            {{-- <span>{{\App\CPU\translate('empty_cart')}}</span> --}}
        @endif
        <div class="d-flex justify-content-between" id="cart-price">
            <span class="cart_title">{{\App\CPU\translate('Harga_sewa_perbulan')}}</span>
            <span class="cart_value">
                {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
            </span>
        </div>
        <div class="d-flex justify-content-between" id="cart-tax">
            <span class="cart_title">{{\App\CPU\translate('tax')}}</span>
            <span class="cart_value">
                @php($tax = $product->unit_price * $product->tax/100)
                {{\App\CPU\Helpers::currency_converter($tax)}}
            </span>
        </div>
        {{-- <div class="d-flex justify-content-between">
            <span class="cart_title">{{\App\CPU\translate('shipping')}}</span>
            <span class="cart_value">
                {{\App\CPU\Helpers::currency_converter($total_shipping_cost)}}
            </span>
        </div> --}}
        <div class="d-flex justify-content-between mt-2" id="cart-discount">
            <span class="cart_title">{{\App\CPU\translate('discount')}}</span>
            <span class="cart_value text-success">
                - @if ($product->discount_type == 'percent')
                {{round($product->discount,2)}}%
                @elseif($product->discount_type =='flat')
                {{\App\CPU\Helpers::currency_converter($product->discount)}}
                @endif
            </span>
        </div>
        @if(session()->has('coupon_discount'))
            <div class="d-flex justify-content-between">
                <span class="cart_title">{{\App\CPU\translate('coupon_code')}}</span>
                <span class="cart_value" id="coupon-discount-amount">
                    - {{session()->has('coupon_discount')?\App\CPU\Helpers::currency_converter(session('coupon_discount')):0}}
                </span>
            </div>
            @php($coupon_dis=session('coupon_discount'))
        @else
            {{-- <div class="mt-2">
                <form class="needs-validation" method="post" novalidate id="coupon-code-ajax">
                    <div class="form-group">
                        <input class="form-control input_code" type="text" name="code" placeholder="{{\App\CPU\translate('Coupon code')}}"
                               required>
                        <div class="invalid-feedback">{{\App\CPU\translate('please_provide_coupon_code')}}</div>
                    </div>
                    <button class="btn btn-primary btn-block" type="button" onclick="couponCode()">{{\App\CPU\translate('apply_code')}}
                    </button>
                </form>
            </div> --}}
            @php($coupon_dis=0)
        @endif
        <hr class="mt-2 mb-2">
        <div class="d-flex justify-content-between" id="cart-total">
            <span class="cart_title">{{\App\CPU\translate('total')}}</span>
            <span class="cart_value">
                {{\App\CPU\Helpers::currency_converter(
                    $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))+ $tax)}}
            </span>
        </div>

        {{-- <div class="d-flex justify-content-center">
            <span class="cart_total_value mt-2">
                {{\App\CPU\Helpers::currency_converter($sub_total+$total_tax+$total_shipping_cost-$coupon_dis-$total_discount_on_product)}}
            </span>
        </div> --}}
    </div>
</aside>
