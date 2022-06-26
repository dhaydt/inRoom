@extends('layouts.front-end.app')
@section('title',\App\CPU\translate('My_kost'))

@push('css_or_js')
<style>
    .msg-option {
            /* display: none; */
    }
    .chatInputBox {
        width: 100%;
    }

    .go-to-chatbox {
        width: 100%;
        text-align: center;
        padding: 5px 0px;
        display: none;
    }
    .booking-col{
        border: 1px solid #d1d1d1;
        border-radius: 8px;
        padding: 10px 17px;
    }
    .headerTitle {
        font-weight: 600;
        color: #7f7f7f;
    }
    .img-booking{
        height: 84px;
        width: 90px;
        border-radius: 5px;
    }

    .card-header .status {
        font-weight: 600;
    }
    .img-kos{
        height: 15px;
    }
    .title-kost {
        font-weight: 600;
    }
    .room-info{
        font-size: 14px;
        font-weight: 500;
    }
    .dated{
        color: #787878;
        font-size: 14px;
    }
    .date-date{
        font-size: 14px;
        font-weight: 600;
    }
    .see-more {
        font-size: 14px;
        font-weight: 600;
    }
    .price-card{
        font-weight: 600;
    }
    .price-card .satuan{
        font-weight: 400;
    }
    .fasilitas{
        font-weight: 600;
    }
    .data-penyewa {
        border: 1px solid #d1d1d1;
        border-radius: 5px;
    }
    .field{
        font-size: 14px;
        font-weight: 500;
        color: #383746;
        text-transform: capitalize;
    }
    .content{
        font-size: 14px;
        font-weight: 600;
    }
    .chatInputBox{
        border-radius: 7px;
    }
    @media(max-width: 500px){
        .margin-auto{
            margin-top: 50px !important;
        }
        .booking-col{
            border: none;
            padding: 10px 0;
        }
        .booking-frame{
            display: flex;
            width: 100%;
            justify-content: center;
            margin-bottom: 10px;
        }
        .booking-frame .img-booking{
            height: 110px;
            width: 150px;
        }
    }
</style>
@endpush

@section('content')
<div class="container pb-5 mb-2 mb-md-4 mt-3 rtl margin-auto">
    <div class="row">
            @include('web-views.partials._profile-aside')
        <section class="col-lg-9 mt-2 col-md-9 booking-col">
            <h1 class="h3 float-left headerTitle w-100">{{\App\CPU\translate('My_kost')}}</h1>
            @foreach ($orders as $order)
            @php($bookeds = App\Model\Booked::where('order_id', $order->id)->get())

            <div class="card w-100 mt-4">
                <div class="card-header">
                    @if ($order->order_status == 'pending')
                    <span class="status text-info">Tunggu Konfirmasi</span>
                    @endif
                    @if ($order->order_status == 'processing')
                    <span class="status text-warning">Butuh Pembayaran</span>
                    @endif
                    @if ($order->order_status == 'delivered')
                    <span class="status text-success">Terbayar</span>
                    @endif
                    @if ($order->order_status == 'canceled')
                    <span class="status text-danger">Booking dibatalkan</span>
                    @endif
                    {{-- {{ dd($orders) }} --}}
                    @php($detail = json_decode($order->details[0]->product_details))
                    @php($district = strtolower($detail->kost->district))
                    @php($city = strtolower($detail->kost->city))
                </div>
                <hr class="line">
                <div class="card-body">
                    <div class="row">
                        <div class="booking-frame">
                            <img class="img-booking mr-3"
                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                            src="{{asset('storage/product')}}/{{json_decode($detail->images)[0]}}" alt="">
                        </div>
                        <div class="kost-detail d-flex flex-column">
                            <span class="title-kost capitalize">{{ $detail->kost->name }} {{ $detail->type }} {{ $district }} {{ $city }}</span>
                            <div class="status mt-1">
                                <img src="{{ asset('assets/front-end/img/room.png') }}" class="img-kos" alt="">
                                <span class="capitalize room-info ml-2">
                                    @if ($order->roomDetail_id == NULL)
                                    Kamar belum dikonfirmasi
                                    @elseif ($order->roomDetail_id == 'ditempat')
                                    Dipilih ditempat
                                    @else
                                        @if (isset($order->room[0]->name))
                                        Kamar {{ $order->room[0]->name }}
                                        @else
                                            <span class="badge badge-danger">Data kamar hilang</span>
                                        @endif
                                    @endif

                                </span>
                            </div>
                            <div class="date row mt-1">
                                <div class="col-12">
                                    <img src="{{ asset('assets/front-end/img/date.png') }}" style="height: 15px" alt="">
                                    <span class="ml-2 dated">Tanggal masuk</span>
                                </div>
                                {{-- @php($add = 2)
                                    {{ date("Y-m-d", strtotime("+".$add."month", strtotime($order->mulai))) }} --}}
                                    @php($date = Carbon\Carbon::parse($order->mulai)->isoFormat('dddd, D MMMM Y'))
                                    <div class="ml-4 mt-1">
                                        <span class="date-date">{{ App\CPU\Helpers::dateChange($date) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="more-content d-none" id="more_content{{ $order->id }}">
                        <div class="row justify-content-center px-4 mb-4">
                            <div class="price mt-3 col-md-9 px2">
                                <span class="price-card">{{\App\CPU\Helpers::currency_converter($order->order_amount)}} <span class="satuan">/bulan</span></span>
                            </div>
                            <div class="col-md-9">
                                <div class="btn-fasilitas">
                                    <a href="javascript:" class="fasilitas capitalize text-success" data-toggle="modal" data-target="#exampleModal">
                                        Lihat fasilitas
                                    </a>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                ...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php($user = auth('customer')->user())
                            @if (isset($user))
                        <div class="col-md-12 data-penyewa p-3 mt-3">
                            <span class="title-kost capitalize">
                                data penyewa
                            </span>
                            <div class="row mt-3">
                                <div class="col-12 d-flex justify-content-between">
                                    <span class="field">Nama</span>
                                    <span class="content">{{ $user->f_name }} {{ $user->l_name }}</span>
                                </div>
                                <div class="col-12 d-flex justify-content-between mt-3">
                                    <span class="field">Nomor Handphone</span>
                                    <span class="content">+62{{ (int)$user->phone }}</span>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-md-12 data-penyewa p-3 mt-3">
                            <span class="title-kost capitalize">
                                data penyewa
                            </span>
                            <div class="row mt-3">
                                <div class="col-12 d-flex justify-content-between">
                                    <span class="field">Nama</span>
                                    <span class="content badge badge-danger">data penyewa tidak valid</span>
                                </div>
                                <div class="col-12 d-flex justify-content-between mt-3">
                                    <span class="field">Nomor Handphone</span>
                                    <span class="content badge badge-danger">data penyewa tidak valid</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-12 data-penyewa p-3 mt-3">
                            <span class="title-kost capitalize">
                                detail booking
                            </span>
                            <div class="row mt-3">
                                <div class="col-12 d-flex justify-content-between">
                                    <span class="field">ID Booking</span>
                                    <span class="content">{{ $order->id }}</span>
                                </div>
                                <div class="col-12 d-flex justify-content-between mt-3">
                                    <span class="field">Tanggal Masuk</span>
                                    <span class="content">{{ App\CPU\Helpers::dateChange($date) }}</span>
                                </div>
                                <div class="col-12 d-flex justify-content-between mt-3">
                                    <span class="field">Jumlah penyewa</span>
                                    <span class="content">1</span>
                                </div>
                                <div class="col-12 d-flex justify-content-between mt-3">
                                    <span class="field">Durasi sewa</span>
                                    <span class="content">{{ $order->durasi }} bulan</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 data-penyewa p-3 mt-3">
                            <span class="title-kost capitalize">
                                detail pembayaran
                            </span>
                            @if (count($bookeds) > 0)
                            @foreach ($bookeds as $b)
                                <div class="row mt-5">
                                    <div class="col-12 d-flex justify-content-between">
                                        <span class="field">Bulan ke-</span>
                                        <span class="content" style="font-size: 25px;">
                                            @if ($b->bulan_ke == 0)
                                                LUNAS
                                            @else
                                                {{ $b->bulan_ke }}
                                            @endif
                                        </span>
                                    </div>

                                    <div class="col-12 d-flex justify-content-between mt-3">
                                        <span class="field">Status</span>
                                        <span class="badge content {{ $b->payment_status == 'paid' ? 'badge-success' : 'badge-danger' }}">{{ $b->payment_status }}</span>
                                    </div>
                                    <div class="col-12 d-flex justify-content-between mt-3">
                                        <span class="field">Tanggal Bayar</span>
                                        @if ($b->payment_status == 'paid')
                                        @php($datePay = Carbon\Carbon::parse($order->updated_at)->isoFormat('dddd, D MMMM Y'))
                                            <span class="content">{{ App\CPU\Helpers::dateChange($datePay) }}</span>
                                        @else
                                            <span class="content">-</span>
                                        @endif
                                    </div>
                                    @if ($b->payment_status == 'unpaid')
                                    @php($deadline = Carbon\Carbon::parse($b->deadline)->isoFormat('dddd, D MMMM Y'))
                                    <div class="col-12 d-flex justify-content-between mt-3">
                                        @if ($b->total_durasi !==$b->bulan_ke)
                                        <span class="field">Jatuh tempo pembayaran</span>
                                        @else
                                        <span class="field">Tanggal habis kos</span>
                                        @endif
                                        <span class="content">{{ App\CPU\Helpers::dateChange($deadline) }}</span>
                                    </div>
                                    @endif
                                    <div class="col-12 d-flex justify-content-between mt-3">
                                        <span class="field">Jumlah bayar</span>
                                        <span class="content">{{ App\CPU\Helpers::currency_converter($b->current_payment) }}</span>
                                    </div>
                                    @if ($b->total_durasi !==$b->bulan_ke)
                                    <div class="col-12 d-flex justify-content-between mt-3">
                                        <span class="field">Tanggal Bayar berikutnya</span>
                                        <span class="content">
                                            @if ($b->bulan_ke == 0)
                                                Lunas
                                            @else
                                            @php($next = Carbon\Carbon::parse($b->next_payment_date)->isoFormat('dddd, D MMMM Y'))
                                                {{ App\CPU\Helpers::dateChange($next) }}
                                            @endif
                                        </span>
                                    </div>
                                    @endif
                                    <div class="col-12 d-flex justify-content-between mt-3">
                                        <span class="field">Nominal pembayaran berikutnya</span>
                                        <span class="content badge badge-warning">{{ App\CPU\Helpers::currency_converter(($b->next_payment)) }}</span>
                                    </div>
                                    @if ($b->payment_status == "unpaid")
                                        <div class="col-12 mt-4">
                                            <div class="d-flex justify-content-end">
                                                <a href="{{ route('checkout-next-payment', ["id" => $b->id]) }}" class="btn btn-success btn-sm">Bayar sekarang</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <hr class="mt-4">
                                @endforeach
                            @endif
                        </div>
                    </div>

                    @if ($order->order_status == 'pending')
                    <div class="col-12 d-flex justify-content-end mb-4 pr-3">
                        <button onclick="route_alert('{{ route('order-cancel',[$order->id]) }}','{{\App\CPU\translate('ingin_membatalkan_bookingan_ini ?')}}')" class="btn btn-outline-success capitalize">
                            Batalkan booking
                        </button>
                    </div>
                    @endif
                </div>
                <div class="col-12 text-center mb-2">
                    <a href="javascript:" id="lengkap{{ $order->id }}" class="see-more text-success" onclick="lihat({{ $order->id }})">
                        Lihat selengkapnya
                        <i class="fa fa-chevron-down ml-2"></i>
                    </a>
                    <a href="javascript:" id="sedikit{{ $order->id }}" class="d-none see-more text-success" onclick="hide({{ $order->id }})">
                        Lihat lebih sedikit
                        <i class="fa fa-chevron-up ml-2"></i>
                    </a>
                </div>
                <div class="card-footer">
                    <div class="row">
                        @if ($order->seller_is != 'admin')
                            @if($order->order_status == 'pending' || $order->order_status == 'delivered')
                            <div class="col-12 d-flex justify-content-end" id="contact-seller">
                                {{-- <button class="btn btn-outline-success">
                                    Chat pemilik
                                </button> --}}
                                <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#staticBackdrop">
                                    Chat pemilik
                                  </button>
                            </div>
                            @php($seller = $order->details[0]->product->kost->seller_id)
                            @php($kost = $order->details[0]->product->kost->id)

                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Chat pemilik kos</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row msg-option" id="msg-option">
                                            <form action="">
                                                <input type="text" class="seller_id" hidden seller-id="{{$seller }}">
                                                <textarea shop-id="{{$kost}}" class="chatInputBox"
                                                            id="chatInputBox" rows="5"> </textarea>


                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="go-to-chatbox" id="go_to_chatbox">
                                                <a href="{{route('chat-with-seller')}}" class="btn btn-primary" id="go_to_chatbox_btn">
                                                    {{\App\CPU\translate('go_to')}} {{\App\CPU\translate('chatbox')}} </a>
                                            </div>
                                            <button class="btn btn-secondary" style="color: white;" data-dismiss="modal"
                                            id="cancelBtn">{{\App\CPU\translate('cancel')}}
                                            </button>
                                            <button class="btn btn-primary" style="color: white;"
                                                    id="sendBtn">{{\App\CPU\translate('send')}}</button>
                                        </form>
                                    </div>
                                </div>
                                </div>
                            </div>


                            @endif
                        @else

                        @if($order->order_status == 'pending' || $order->order_status == 'delivered')
                        <div class="col-12 d-flex justify-content-end">
                            <a href="{{ route('contacts') }}" target="_blank" class="btn btn-outline-success text-success">
                                Chat Admin InRoom
                            </a>
                        </div>
                        @endif
                        @endif

                        @if($order->order_status == 'processing')
                        <div class="col-12 d-flex justify-content-end">
                            <a href="{{ route('checkout-payment', ['order_id' => $order->id]) }}" class="btn btn-success">
                                Bayar sekarang
                            </a>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
            @endforeach
        </section>
    </div>
</div>
@endsection
@push('script')
    <script>
        function lihat(val){
            $('#more_content' +  val).removeClass('d-none')
            $('#lengkap'+  val).addClass('d-none')
            $('#sedikit'+  val).removeClass('d-none')
        }
        function hide(val){
            $('#more_content'+  val).addClass('d-none')
            $('#lengkap'+  val).removeClass('d-none')
            $('#sedikit'+  val).addClass('d-none')
        }
    </script>
    <script>
         $('#contact-seller').on('click', function (e) {
            // $('#seller_details').css('height', '200px');
            $('#seller_details').animate({'height': '276px'});
            $('#msg-option').css('display', 'block');
        });
        $('#sendBtn').on('click', function (e) {
            e.preventDefault();
            let msgValue = $('#msg-option').find('textarea').val();
            let data = {
                message: msgValue,
                shop_id: $('#msg-option').find('textarea').attr('shop-id'),
                seller_id: $('.msg-option').find('.seller_id').attr('seller-id'),
            }
            if (msgValue != '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "post",
                    url: '{{route('messages_store')}}',
                    data: data,
                    success: function (respons) {
                        console.log('send successfully');
                    }
                });
                $('#chatInputBox').val('');
                $('#msg-option').css('display', 'none');
                $('#contact-seller').find('.contact').attr('disabled', '');
                $('#seller_details').animate({'height': '125px'});
                $('#go_to_chatbox').css('display', 'block');
            } else {
                console.log('say something');
            }
        });
        $('#cancelBtn').on('click', function (e) {
            e.preventDefault();
            $('#seller_details').animate({'height': '114px'});
            $('#msg-option').css('display', 'none');
        });
    </script>
@endpush
