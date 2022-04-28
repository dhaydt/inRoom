<style>
    .custom-control-input:checked ~ .custom-control-label::before{
        background-color: #4f4f4f !important;
        border-color: #4f4f4f !important;
    }
    .custom-switch .custom-control-input:checked ~ .custom-control-label::before{
        box-shadow: 0 0.375rem 0.875rem -0.3rem #4f4f4f !important;
    }
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
            <span class="cart_title">{{\App\CPU\translate('Harga_sewa')}}</span>
            <span class="cart_value">
                {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
            </span>
        </div>
        <div class="d-flex justify-content-between mt-2" id="cart-discount">
            <span class="cart_title">{{\App\CPU\translate('discount')}} (@if ($product->discount_type == 'percent')
                {{round($product->discount,2)}}%
                @elseif($product->discount_type =='flat')
                {{\App\CPU\Helpers::currency_converter($product->discount)}}
                @endif)
            </span>
            <span class="cart_value text-success">
                - {{\App\CPU\Helpers::currency_converter(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))}}
            </span>
        </div>
        <div class="d-flex justify-content-between" id="cart-tax">
            <span class="cart_title">{{\App\CPU\translate('tax')}}</span>
            <span class="cart_value">
                @php($tax = $product->unit_price * $product->tax/100)
                + {{\App\CPU\Helpers::currency_converter($tax)}}
            </span>
        </div>
        <div class="d-flex justify-content-between" id="cart-deposit">
            <span class="cart_title">{{\App\CPU\translate('Deposit')}} <small class="text-danger"> (dana akan dikembalikan saat kos berakhir)</small></span>
            <span class="cart_value">
                + {{\App\CPU\Helpers::currency_converter($product->deposit)}}
            </span>
        </div>
        @php($poinUser = session()->get('poin'))
        @if ($poinUser != 0)
        <div class="d-flex justify-content-between" id="cart-poin">
            <span class="cart_title">
                <div class="custom-control custom-switch" style="cursor: pointer;">
                    <input type="checkbox" class="custom-control-input switchPoin" value="off" onchange="usePoin(this.value)" id="customSwitch1" style="cursor: pointer;">
                    <label class="custom-control-label" for="customSwitch1" style="cursor: pointer;">Poin Cashback</label>
                </div>
            </span>
            <span class="cart_value">{{\App\CPU\Helpers::currency_converter($poinUser)}}</span>
        </div>
        @endif
        @if(session()->has('coupon_discount'))
            <div class="d-flex justify-content-between">
                <span class="cart_title">{{\App\CPU\translate('coupon_code')}}</span>
                <span class="cart_value" id="coupon-discount-amount">
                    - {{session()->has('coupon_discount')?\App\CPU\Helpers::currency_converter(session('coupon_discount')):0}}
                </span>
            </div>
            @php($coupon_dis=session('coupon_discount'))
        @else
            @php($coupon_dis=0)
        @endif
        <hr class="mt-2 mb-2">
        <div class="d-flex justify-content-between" id="cart-total">
            <span class="cart_title">{{\App\CPU\translate('total')}}</span>
            <span class="cart_value" id="total-val">
                {{\App\CPU\Helpers::currency_converter(
                    $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))+ $tax + $product->deposit)}}
            </span>
        </div>
    </div>
</aside>
