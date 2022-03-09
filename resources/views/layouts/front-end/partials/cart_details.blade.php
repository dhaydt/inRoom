<style>
    div.feature_header {
        justify-content: start;
    }

    div.feature_header span {
        text-transform: capitalize;
    }

    .bg-c-label.bg-c-label--rainbow-white {
        padding: 2px 5px;
        font-weight: 600;
        font-size: 14px;
        text-transform: capitalize;
    }

    .quantity {
        position: relative;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }

    .quantity input {
        width: 60px;
        height: 28px;
        line-height: 1.65;
        float: left;
        display: block;
        padding: 0;
        margin: 0;
        padding-left: 37px;
        border: none;
    }

    .quantity input:focus {
        outline: 0;
    }

    .quantity-nav {
        float: left;
        position: relative;
        height: 42px;
    }

    .quantity-button {
        position: relative;
        cursor: pointer;
        border-left: 1px solid #eee;
        width: 20px;
        text-align: center;
        color: #333;
        font-size: 13px;
        font-family: "Trebuchet MS", Helvetica, sans-serif !important;
        line-height: 1.7;
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%);
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -o-user-select: none;
        user-select: none;
    }

    .quantity-button.quantity-up {
        position: absolute;
        height: 62%;
        left: 30px;
        width: 30px;
        border: 1px solid #d1d1d1;
        border-radius: 5px;
        font-weight: 800;
        background-color: #d1d1d1;
        color: #000;
    }

    .quantity-button.quantity-down {
        position: absolute;
        left: -40px;
        height: 62%;
        width: 30px;
        border: 1px solid #d1d1d1;
        border-radius: 5px;
        font-weight: 800;
        background-color: #d1d1d1;
        color: #000;
    }
    textarea{
        border: 1px solid #d1d1d1;
        padding: 8px;
        border-radius: 5px;
        font-size: 14px;
        width: 98%;
    }
</style>
<!-- Grid-->
@php($cart=\App\Model\Cart::with('product')->where(['customer_id' =>
auth('customer')->id()])->get()->groupBy('cart_group_id'))

<div class="row">
    <!-- List of items-->
    <section class="col-lg-8">
        <div class="back-btn">
            <div class="col-6">
                <a href="{{route('home')}}" class="text-grey">
                    <i class="fa fa-{{Session::get('direction') === " rtl" ? 'forward' : 'backward' }} px-1"></i>
                    {{\App\CPU\translate('kembali')}}
                </a>
            </div>
        </div>
        <div class="feature_header mt-4">
            <span class="pl-0">{{ \App\CPU\translate('pengajuan_sewa')}}</span>
        </div>
        @include('web-views.partials._checkout-steps',['step'=>1])
        <div class="cart_information">
            @foreach($cart as $group_key=>$group)
            @foreach($group as $cart_key=>$cartItem)
            @if($cart_key==0)
            @if($cartItem->seller_is=='admin')
            {{-- {{\App\CPU\Helpers::get_business_settings('company_name')}} --}}
            @else
            {{-- {{\App\Model\Shop::where(['seller_id'=>$cartItem['seller_id']])->first()->name}} --}}
            @endif
            @endif
            <div class="cart_item mb-2">
                <div class="row">
                    <div class="col-md-7 col-sm-6 col-9 d-flex align-items-center">
                        <div class="media">
                            <div class="media-header d-flex justify-content-center align-items-center {{Session::get('direction') === "
                                rtl" ? 'ml-2' : 'mr-2' }}">
                                <a href="{{route('product',$cartItem['slug'])}}"
                                    style="overflow: hidden; border-radius: 6px;">
                                    <img style="height: 82px;"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{\App\CPU\ProductManager::product_image_path('product')}}/{{$cartItem['thumbnail']}}"
                                        alt="Product">
                                </a>
                            </div>

                            <div class="media-body d-flex justify-content-center align-items-center">
                                <div class="cart_product">
                                    <div class="bg-c-label bg-c-label--rainbow bg-c-label--rainbow-white">{{
                                        $cartItem->product->kost->penghuni }}</div>
                                    {{-- <div class="product-title">
                                        <a href="javascript:"></a>
                                    </div> --}}
                                    @php($city = strtolower($cartItem->product->kost['city']))
                                    @php($district = strtolower($cartItem->product->kost['district']))
                                    <div class=" text-accent">
                                        {{$cartItem->product->kost->name}} {{$district}}
                                    </div>
                                    <div class="capitalize" style="font-size: 14px!important;color: grey!important;">
                                        {{$city}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-2 col-3 d-flex align-items-center">
                        <input type="hidden" name="quantity[{{ $cartItem['id'] }}]" value="1">
                    </div>
                    <div
                        class="col-md-4 col-sm-4 offset-4 offset-sm-0 text-center d-flex justify-content-between align-items-center">
                        <div class="">
                            <div class=" text-accent">
                                {{
                                \App\CPU\Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*$cartItem['quantity'])
                                }}
                            </div>
                        </div>
                        <div style="margin-top: 3px;">
                            <button class="btn btn-link px-0 text-danger"
                                onclick="removeFromCart({{ $cartItem['id'] }})" type="button"><i
                                    class="czi-close-circle {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2'
                                    }}"></i>
                            </button>
                        </div>
                    </div>
                    @php($user = auth('customer')->user())
                    <div class="row px-2 mt-3 w-100">
                        <div class="col-12 px-1">
                            <div class="penyewa">
                                <h3 class="title-section">Informasi penyewa </h3>
                            </div>
                            <div class="data-penyewa pl-2">
                                <div role="listitem"
                                    class="booking-form-tenant-info-item booking-form-tenant-info__item">
                                    <p class="bg-c-text bg-c-text--body-1 ">Nama penyewa</p>
                                    <p
                                        class="booking-form-tenant-info-item__value bg-c-text bg-c-text--body-4 text-grey capitalize">
                                        {{ $user->f_name }} {{ $user->l_name }}
                                    </p>
                                </div>
                                <div role="listitem"
                                    class="booking-form-tenant-info-item booking-form-tenant-info__item mt-3">
                                    <p class="bg-c-text bg-c-text--body-1 ">Nomor handphone</p>
                                    <p
                                        class="booking-form-tenant-info-item__value bg-c-text bg-c-text--body-4 text-grey">
                                        {{ $user->phone }}
                                    </p>
                                </div>
                                <div role="listitem"
                                    class="booking-form-tenant-info-item booking-form-tenant-info__item mt-3">
                                    <p class="bg-c-text bg-c-text--body-1 ">Jenis kelamin</p>
                                    <p
                                        class="booking-form-tenant-info-item__value bg-c-text bg-c-text--body-4 text-grey capitalize">
                                        {{ $user->kelamin }}
                                    </p>
                                </div>
                                <div role="listitem"
                                    class="booking-form-tenant-info-item booking-form-tenant-info__item mt-3">
                                    <p class="bg-c-text bg-c-text--body-1 ">Pekerjaan</p>
                                    <p
                                        class="booking-form-tenant-info-item__value bg-c-text bg-c-text--body-4 text-grey capitalize">
                                        {{ $user->pekerjaan }}
                                    </p>
                                </div>
                            </div>
                            <hr class="border_section" style="margin: 50px 0 50px 0;">
                        </div>
                        <div class="col-12 px1">
                            <div class="penyewa">
                                <h3 class="title-section">Jumlah penyewa</h3>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4" style="margin-left: 12px;">
                                    <div class="data-penyewa pl-2 d-flex-justify-content-center">
                                        <div class="quantity">
                                            <input disabled type="number" name="penyewa" min="1" max="9" step="1" value="1">
                                        </div>
                                    </div>
                                    <span style="margin-left: 40px">Orang</span>
                                </div>
                            </div>
                            <hr class="border_section" style="margin: 50px 0 50px 0;">
                        </div>
                        <div class="col-12 px1">
                            <div class="penyewa mb-3">
                                <h3 class="title-section mb-1">Catatan tambahan</h3>
                                <small>Penjelasan terkait pengajuan sewa dan transaksimu</small>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="data-penyewa pl-2 d-flex-justify-content-center">
                                        <textarea name="catatan_tambahan" id="" cols="30" rows="3" placeholder="Misal: saya membawa barang elektronik berupa laptop"></textarea>
                                    </div>
                                </div>
                            </div>
                            <hr class="border_section" style="margin: 50px 0 50px 0;">
                        </div>
                        <div class="col-12 px1">
                            <div class="penyewa mb-3">
                                <h3 class="title-section mb-1">Tanggal mulai ngekos</h3>
                            </div>
                            @php($date = Carbon\Carbon::parse($cartItem->mulai)->isoFormat('dddd, D MMMM Y'))
                            <div class="row mt-3 pl-3">
                               <p class="text-grey">{{ App\CPU\Helpers::dateChange($date) }}</p>
                            </div>
                            <hr class="border_section" style="margin: 50px 0 50px 0;">
                        </div>
                    </div>
                </div>
            </div>

            {{-- @endif --}}
            @endforeach
            <div class="mt-3"></div>
            @endforeach

            @if( $cart->count() == 0)
            <div class="d-flex justify-content-center align-items-center">
                <h4 class="text-danger text-capitalize">{{\App\CPU\translate('cart_empty')}}</h4>
            </div>
            @endif
        </div>
        <div class="row pt-2 justify-content-center">
            <div class="col-12">
                <a href="{{route('checkout-details')}}" class="w-100 btn btn-primary pull-{{Session::get('direction') === "
                    rtl" ? 'left' : 'right' }}">
                    {{\App\CPU\translate('Ajukan_sewa')}}
                    {{-- <i class="fa fa-{{Session::get('direction') === " rtl" ? 'backward' : 'forward' }} px-1"></i> --}}
                </a>
            </div>
        </div>
    </section>
    <!-- Sidebar-->
    @include('web-views.partials._order-summary')
</div>


<script>
    cartQuantityInitialize();

    function set_shipping_id(id, cart_group_id) {
        $.get({
            url: '{{url('/')}}/customer/set-shipping-method',
            dataType: 'json',
            data: {
                id: id,
                cart_group_id: cart_group_id
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                location.reload();
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }
</script>
@push('script')
<script>
    $(document).ready(function(){
            jQuery('<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>').insertAfter('.quantity input');
    jQuery('.quantity').each(function() {
      var spinner = jQuery(this),
        input = spinner.find('input[type="number"]'),
        btnUp = spinner.find('.quantity-up'),
        btnDown = spinner.find('.quantity-down'),
        min = input.attr('min'),
        max = input.attr('max');

      btnUp.click(function() {
        var oldValue = parseFloat(input.val());
        if (oldValue >= max) {
          var newVal = oldValue;
        } else {
          var newVal = oldValue + 1;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

      btnDown.click(function() {
        var oldValue = parseFloat(input.val());
        if (oldValue <= min) {
          var newVal = oldValue;
        } else {
          var newVal = oldValue - 1;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

    });
        })
</script>
@endpush
