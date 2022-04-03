@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Pembayaran'))

@push('css_or_js')
<style>
    .payment-title {
        font-weight: 600;
        color: #747474;
    }

    span.subtitle {
        color: #5b5b5b;
        font-weight: 500;
        font-size: 18px;
    }

    span.content {
        font-weight: 400;
        color: #818181;
    }

    span.content.price {
        width: 75%;
        text-align: right;
        font-size: 20px;
        color: #1a1a1a;
    }

    hr.line {
        border-top: 1px solid #cdcdcd;
    }

    .title-card {
        font-size: 16px;
        font-weight: 500;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }

    .status-kos span {
        border: 1px solid #d1d1d1;
        text-transform: capitalize;
        border-radius: 5px;
        padding: 5px;
        margin-right: 10px;
        color: #5b5b5b;
        font-weight: 600;
        font-size: 12px;
    }

    span.price {
        font-size: 14px;
        color: #686868;
        font-weight: 600
    }

    .mulai .title {
        font-size: 14px;
    }

    .mulai span.content {
        font-weight: 600;
        color: #3e3e3e;
    }

    .info-box {
        border: 1px solid #747474;
        border-radius: 5px;
        padding: 5px;
    }

    .info-box .fa {
        color: #1e90ff;
    }

    .info-box span {
        font-size: 14px;
        font-weight: 500;
        color: #747474;
    }

    .title-payment {
        font-size: 20px;
        font-weight: 600;
        color: #2c2c2c;
    }

    span.bank {
        font-size: 16px;
        color: #4e4e4e;
    }

    .bank-logo img {
        height: 25px;
        ;
    }

    .bank-logo i {
        font-size: 12px
    }
</style>

{{--stripe--}}
<script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
<script src="https://js.stripe.com/v3/"></script>
{{--stripe--}}
@endpush

@section('content')
<!-- Page Content-->
<div class="container pb-5 mb-2 mb-md-4 rtl" style="text-align: {{Session::get('direction') === " rtl" ? 'right'
    : 'left' }};">
    <div class="row">
        <section class="col-lg-8">
            <hr>
            <div class="checkout_details mt-3">
                @include('web-views.partials._checkout-steps',['step'=>3])

                <h2 class="pb-5 mb-3 mt-4 payment-title">{{\App\CPU\translate('Pembayaran')}}</h2>
                <div class="form-list">
                    <span class="subtitle mb-1 d-block">No. Booking</span>
                    <span class="content">{{ $order->id }}</span>
                    <hr class="line mt-4 mb-4">
                </div>
                <div class="form-list">
                    <span class="subtitle mb-1 d-block capitalize">Jenis pembayaran</span>
                    <span class="content">Bayar sewa kos</span>
                    <hr class="line mt-4 mb-4">
                </div>
                <div class="form-list">
                    <span class="subtitle mb-1 d-block capitalize">Metode pembayaran</span>
                    <div class="row">
                        <div class="col-6">
                            <span class="content" id="payment-method">Pilih metode pembayaran</span>
                        </div>
                        <input type="hidden" name="payment" value="" id="payment">
                        <div class="col-6 text-right">
                            <a href="javascript:" type="button" class="text-success" data-toggle="modal"
                                data-target="#exampleModal">
                                Ubah
                            </a>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Pilih metode pembayaran</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body mb-4">
                                        <div class="row">
                                            <div class="section-payment w-100">
                                                <div class="title-payment mb-3">
                                                    <span>
                                                        Transfer
                                                    </span>
                                                </div>
                                                <div class="content-payment">
                                                    <a href="javascript:" onclick="payment('bni')" class="row">
                                                        <div class="col-9">
                                                            <span class="bank">
                                                                Bank BNI
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="col-3 bank-logo d-flex justify-content-between align-items-center">
                                                            <img src="{{ asset('assets/front-end/img/bni.png') }}"
                                                                style="height: 16px;" alt="BNI">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </div>
                                                    </a>
                                                    <a href="javascript:" onclick="payment('bri')" class="row mt-3">
                                                        <div class="col-9">
                                                            <span class="bank">
                                                                Bank BRI
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="col-3 bank-logo d-flex justify-content-between align-items-center">
                                                            <img src="{{ asset('assets/front-end/img/bri.png') }}"
                                                                style="height: 22px;" alt="BRI">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </div>
                                                    </a>
                                                    <a href="javascript:" onclick="payment('bca')" class="row mt-3">
                                                        <div class="col-9">
                                                            <span class="bank">
                                                                Bank BCA
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="col-3 bank-logo d-flex justify-content-between align-items-center">
                                                            <img src="{{ asset('assets/front-end/img/bca.png') }}"
                                                                style="height: 20px;" alt="BCA">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </div>
                                                    </a>
                                                    <a href="javascript:" onclick="payment('mandiri')" class="row mt-3">
                                                        <div class="col-9">
                                                            <span class="bank">
                                                                Bank Mandiri
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="col-3 bank-logo d-flex justify-content-between align-items-center">
                                                            <img src="{{ asset('assets/front-end/img/mandiri.png') }}"
                                                                alt="mandiri">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </div>
                                                    </a>
                                                </div>
                                                <hr class="mt-3 mb-3">
                                            </div>
                                            <div class="section-payment w-100">
                                                <div class="title-payment mb-3">
                                                    <span>
                                                        Gerai Retail
                                                    </span>
                                                </div>
                                                <div class="content-payment">
                                                    <a href="javascript:" onclick="payment('alfamart')" class="row">
                                                        <div class="col-9">
                                                            <span class="bank">
                                                                Alfamart
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="col-3 bank-logo d-flex justify-content-between align-items-center">
                                                            <img src="{{ asset('assets/front-end/img/alfa.png') }}"
                                                                alt="alfa">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </div>
                                                    </a>
                                                    <a href="javascript:" onclick="payment('indomaret')"
                                                        class="row mt-3">
                                                        <div class="col-9">
                                                            <span class="bank">
                                                                Indomaret
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="col-3 bank-logo d-flex justify-content-between align-items-center">
                                                            <img src="{{ asset('assets/front-end/img/indo.png') }}"
                                                                alt="indo">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </div>
                                                    </a>
                                                </div>
                                                <hr class="mt-3 mb-3">
                                            </div>
                                            <div class="section-payment w-100">
                                                <div class="title-payment mb-3">
                                                    <span>
                                                        Uang Elektronik
                                                    </span>
                                                </div>
                                                <div class="content-payment">
                                                    {{-- <a href="javascript:" onclick="payment('gopay')" class="row">
                                                        <div class="col-9">
                                                            <span class="bank">
                                                                Go PAY
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="col-3 bank-logo d-flex justify-content-between align-items-center">
                                                            <img src="{{ asset('assets/front-end/img/gopay-ready.png') }}"
                                                                style="height: 18px;" alt="gopay">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </div>
                                                    </a> --}}
                                                    <a href="javascript:" onclick="payment('dana')" class="row mt-3">
                                                        <div class="col-9">
                                                            <span class="bank">
                                                                DANA
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="col-3 bank-logo d-flex justify-content-between align-items-center">
                                                            <img src="{{ asset('assets/front-end/img/dana-ready.png') }}"
                                                                style="height: 18px;" alt="dana">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </div>
                                                    </a>
                                                    <a href="javascript:" onclick="payment('ovo')" class="row mt-3">
                                                        <div class="col-9">
                                                            <span class="bank">
                                                                OVO
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="col-3 bank-logo d-flex justify-content-between align-items-center">
                                                            <img src="{{ asset('assets/front-end/img/ovo-ready.png') }}"
                                                                style="height: 18px;" alt="ovo">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </div>
                                                    </a>
                                                    <a href="javascript:" onclick="payment('shopeepay')"
                                                        class="row mt-3">
                                                        <div class="col-9">
                                                            <span class="bank">
                                                                Shopee PAY
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="col-3 bank-logo d-flex justify-content-between align-items-center">
                                                            <img src="{{ asset('assets/front-end/img/shopee.png') }}"
                                                                style="height: 18px;" alt="ovo">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </div>
                                                    </a>
                                                    <a href="javascript:" onclick="payment('linkaja')" class="row mt-3">
                                                        <div class="col-9">
                                                            <span class="bank">
                                                                LinkAja
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="col-3 bank-logo d-flex justify-content-between align-items-center">
                                                            <img src="{{ asset('assets/front-end/img/link.png') }}"
                                                                style="height: 18px;" alt="linkaja">
                                                            <i class="fa fa-chevron-right"></i>
                                                        </div>
                                                    </a>
                                                </div>
                                                <hr class="mt-3 mb-3">
                                            </div>
                                        </div>
                                        @php($cart=\App\Model\Cart::where(['customer_id' =>
                                        auth('customer')->id()])->get()->groupBy('cart_group_id'))

                                        @php($coupon_discount = session()->has('coupon_discount') ?
                                        session('coupon_discount') : 0)
                                        @php($amount = \App\CPU\CartManager::cart_grand_total() - $coupon_discount)
                                        {{-- @endif --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="line mt-4 mb-4">
                </div>
                <div class="form-list d-flex justify-content-between w-100">
                    <span class="subtitle mb-1">Total Pembayaran</span>
                    <input type="hidden" name="value" value="{{ $order->details[0]->price}}" id="price">
                    <span class="content price">{{ \App\CPU\Helpers::currency_converter($order->order_amount)
                        }}</span>
                    <hr class="line mt-4 mb-4">
                </div>


                <!-- Navigation (desktop)-->
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4">
                        {{-- <a class="btn btn-success btn-block disabled" id="send" href="javascript:" onclick="payNow()">
                            <span class="d-none d-sm-inline">{{\App\CPU\translate('Bayar')}}</span>
                            <span class="d-inline d-sm-none">{{\App\CPU\translate('Bayar')}}</span>
                        </a> --}}
                        <form class="needs-validation" target="_blank" method="POST" id="send"
                            action="{{route('xendit-payment.vaInvoice')}}">
                            <input type="hidden" id="type" name="type" value="">
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            {{ csrf_field() }}
                            <button class="btn btn-success w-100" id="pay-btn" type="submit" disabled>
                                {{\App\CPU\translate('Bayar')}}
                            </button>
                        </form>
                    </div>
                    <div class="col-4"></div>
                </div>
            </div>
        </section>
        <!-- Sidebar-->
        @php($detail = json_decode($order->details[0]->product_details))
        @php($sewa = json_decode($order->details[0]->data_penyewa))
        @php($district = strtolower($detail->kost->district))
        @php($city = strtolower($detail->kost->city))
        <div class="side-section col-lg-4 mt-5">
            <div class="card p-2">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <div class="title-card">
                                <span class="">
                                    {{ $detail->kost->name }} {{ $detail->type }} {{ $district }} {{ $city }}
                                </span>
                            </div>
                            <div class="status-kos mt-2">
                                <span>
                                    {{ $detail->kost->penghuni }}
                                </span>
                                <span>
                                    @if ($order->roomDetail_id == 'ditempat')
                                    Pilih ditempat
                                    @else
                                    Kamar {{ $order->room[0]->name }}
                                    @endif
                                </span>
                            </div>
                            <div class="mt-2">
                                <span class="price">
                                    Perbulan - {{\App\CPU\Helpers::currency_converter($order->details[0]->price)}}
                                </span>
                            </div>
                        </div>
                        @php($room = json_decode($order->details[0]->product_details))
                        <div class="col-4 img-div">
                            <img class="w-100" src="{{ asset('storage/product').'/'.json_decode($room->images)[0] }}"
                                style="border-radius: 6px">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @php($date = Carbon\Carbon::parse($order->mulai)->isoFormat('dddd, D MMMM Y'))
                    <div class="row px-0">
                        <div class="col-12 d-flex justify-content-between mt-2 px-0 mulai ">
                            <span class="capitalize title">Tanggal Masuk</span>
                            <span class="content">{{ App\CPU\Helpers::dateChange($date) }}</span>
                        </div>
                        <div class="col-12 d-flex justify-content-between mt-3 px-0 mulai">
                            <span class="capitalize title">Durasi sewa</span>
                            <span class="content">{{ $order->durasi }} Bulan</span>
                        </div>
                    </div>
                </div>
                {{-- {{ dd($order) }} --}}
                <div class="card-footer">
                    <span class="title-card capitalize d-block">
                        Informasi penyewa
                    </span>
                    <span class="content d-block mb-3" style="font-weight: 500;">
                        {{ auth('customer')->user()->f_name.' '.auth('customer')->user()->l_name }}
                    </span>
                    <div class="row info-box">
                        <div class="col-1 text-center">
                            <i class="fa fa-info-circle text-blue mt-1"></i>
                        </div>
                        <div class="col-11">
                            <span>
                                Mohon tunjukan kartu identitas asli pada pemilik kos saat masuk
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')

@if(env('APP_MODE')=='live')
<script id="myScript" src="https://scripts.pay.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout.js"></script>
@else
<script id="myScript" src="https://scripts.sandbox.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout-sandbox.js">
</script>
@endif

<script>
    setTimeout(function () {
            $('.stripe-button-el').hide();
            $('.razorpay-payment-button').hide();
        }, 10)
</script>


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


<script type="text/javascript">
        function capitalizeFirstLetter(string){
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        function payment(val){
            $('#payment-method').text(capitalizeFirstLetter(val))
            $('#type').val(val)
            $('#exampleModal').modal('hide')
            $('#pay-btn').removeAttr('disabled');
        }

        function payNow(){
            var id = {{ $order->id }};
            var payment = $('#payment').val();
            var val = $('#price').val();
            var csrf = $('meta[name="_token"]').attr('content')
            console.log(csrf);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrf
                }
            });
            $.ajax({
                url: "{{route('xendit-payment.vaInvoice')}}",
                method: 'POST',
                data: {
                    "id": id,
                    'type': payment,
                    "value": val
                },
                success: function (data) {
                    if (data.success == 0) {
                        toastr.success('{{\App\CPU\translate('Order is already delivered, You can not change it')}} !!');
                        location.reload();
                    } else {
                        toastr.success('{{\App\CPU\translate('Status Change successfully')}}!');
                        // location.reload();
                    }
                }
            });
        }
</script>
@endpush
