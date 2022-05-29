@extends('layouts.front-end.app')
@section('title',\App\CPU\translate('Pemesanan_saya'))

@push('css_or_js')
<style>
    .auto-cancel{
        font-size: 14px;
        font-weight: 600;
    }
    .datetime{
        font-size: 14px;
        font-weight: 700;
        color: #ff4a4a;
    }
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
    .alasan{
        font-weight: 600;
        color: #8c8c8c;
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
            <h1 class="h3 float-left headerTitle w-100">{{\App\CPU\translate('pemesanan')}}</h1>
            {{-- {{ dd($orders) }} --}}
            @foreach ($orders as $order)
            <div class="card w-100 mt-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            @if ($order->order_status == 'pending')
                            <span class="status text-info">Menunggu Konfirmasi</span>
                            @endif
                            @if ($order->order_status == 'processing')
                            <span class="status text-warning">Menunggu Pembayaran</span>
                            @endif
                            @if ($order->order_status == 'delivered')
                            <span class="status text-success">Sudah Terbayar</span>
                            @endif
                            @if ($order->order_status == 'canceled')
                            <span class="status text-danger">Dibatalkan</span>
                            @endif
                            @if ($order->order_status == 'directPay')
                            <span class="status text-info">Bayar Langsung</span>
                            @endif
                            @if ($order->order_status == 'failed')
                            <span class="status text-danger">Gagal</span>
                            @endif
                            @if ($order->order_status == 'expired')
                            <span class="status text-danger">Waktu berakhir</span>
                            @endif
                        </div>
                        {{-- {{ dd($order->order_status) }} --}}
                        @if ($order->order_status != "canceled" && $order->order_status != 'failed' && $order->order_status != 'expired' && $order->order_status != 'delivered')
                        <div class="col-12 col-md-8 d-flex justify-content-end">
                            @php($date = Carbon\Carbon::now()->toDateTimeString())
                            @if($order->auto_cancel && $date < $order->auto_cancel)
                            <span class="mr-3 auto-cancel text-danger">Otomatis batal :</span>
                            <input type="hidden" id="count{{ $order->id }}" value="{{ $order->auto_cancel }}">
                            <input type="hidden" class="id" value="{{ $order->id }}">
                                <div class="d-flex datetime" id="clockdiv{{ $order->id }}">
                                    <div class="d-flex mr-1">
                                        <span class="days"></span>
                                        <div class="smalltext ml-1">Hari,</div>
                                    </div>
                                    <div class="d-flex mr-1">
                                        <span class="hours"></span>
                                        <div class="smalltext ml-1">:</div>
                                    </div>
                                    <div class="d-flex mr-1">
                                        <span class="minutes"></span>
                                        <div class="smalltext ml-1"> :</div>
                                    </div>
                                    <div class="d-flex mr-1">
                                        <span class="seconds"></span>
                                        {{-- <div class="smalltext">D</div> --}}
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endif
                        @if ($order->alasan_admin)
                            <div class="col col-md-8 text-right">
                                <span class="alasan">{{ $order->alasan_admin }}</span>
                            </div>
                        @elseif($order->alasan_user)
                            <div class="col col-md-8 text-right">
                                <span class="alasan">{{ $order->alasan_user }}</span>
                            </div>
                        @endif
                    </div>
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
                                {{-- {{ dd($order) }} --}}
                                <img src="{{ asset('assets/front-end/img/room.png') }}" class="img-kos" alt="">
                                <span class="capitalize room-info ml-2">
                                    @if ($order->roomDetail_id == NULL)
                                    Kamar belum dikonfirmasi
                                    @elseif ($order->roomDetail_id == 'ditempat')
                                    Dipilih ditempat
                                    @else
                                    Kamar @if (isset($order->room[0]->name))
                                            {{ $order->room[0]->name }}
                                        @else
                                            <span class="badge badge-danger">Invalid room data</span>
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
                                <span class="price-card">{{\App\CPU\Helpers::currency_converter($order->order_amount)}} <span class="satuan">/ {{ $order->durasi }} bulan</span></span>
                            </div>
                            @php($product = json_decode($order->details[0]->product_details))
                            @php($fasilitas = json_decode($product->fasilitas_id))
                            <div class="col-md-9">
                            <div class="btn-fasilitas">
                                <a href="javascript:" class="fasilitas capitalize text-success" data-toggle="modal" data-target="#exampleModal{{ $order->id }}">
                                    Lihat fasilitas
                                </a>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{ $order->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" style="top: 30%;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Fasilitas kamar</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row justify-content-center">
                                            @if ($fasilitas != [])
                                            @foreach ($fasilitas as $f)
                                            <div class="col-6 text-left capitalize">
                                                <img onerror="this.src='{{asset('assets/front-end/img/bantal.png')}}'" class="mr-3" src="{{ asset('assets/front-end/img').'/'.strtolower($f).'.png' }}" alt="broken" style="height: 23px">
                                                {{ App\CPU\Helpers::fasilitas($f) }}
                                            </div>
                                            @endforeach
                                            @else
                                            <div class="col-12 text-center">
                                                tidak ada fasilitas di inputkan
                                            </div>
                                            @endif
                                        </div>

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
                                @php($order_date = Carbon\Carbon::parse($order->created_at)->isoFormat('dddd, D MMMM Y h:mm'))
                                <div class="col-12 d-flex justify-content-between mt-3">
                                    <span class="field">Tanggal Booking</span>
                                    <span class="content">{{ App\CPU\Helpers::dateChange($order_date) }}</span>
                                </div>
                                <div class="col-12 d-flex justify-content-between mt-3">
                                    <span class="field">Tanggal Masuk</span>
                                    <span class="content">{{ App\CPU\Helpers::dateChange($date) }}</span>
                                </div>
                                <div class="col-12 d-flex justify-content-between mt-3">
                                    <span class="field">Jumlah penyewa</span>
                                    <span class="content">{{ $order->jumlah_penyewa }} Orang</span>
                                </div>
                                <div class="col-12 d-flex justify-content-between mt-3">
                                    <span class="field">Durasi sewa</span>
                                    <span class="content">{{ $order->durasi }} bulan</span>
                                </div>
                                <div class="col-12 d-flex justify-content-between mt-3">
                                    <span class="field">Pembayaran pertama</span>
                                    <span class="content">{{ App\CPU\Helpers::currency_converter($order->firstPayment ? $order->firstPayment : $order->order_amount)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($order->order_status == 'pending')
                    <div class="col-12 d-flex justify-content-end mb-4 pr-3">
                        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#batalkan{{ $order->id }}">
                            Batalkan booking
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="batalkan{{ $order->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Pilih alasan pembatalan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <select id="alasan{{ $order->id }}" class="custom-select custom-select-lg mb-3" name="alasan">
                                        <option value="">-- Pilih alasan pembatalan --</option>
                                        <option value="Sudah menemukan kamar lain">Sudah menemukan kamar lain</option>
                                        <option value="Ingin merubah bookingan">Ingin merubah bookingan</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="batalkan({{ $order->id }})">Batalkan</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!-- modal batalkan -->
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
                            @if ($order->order_status == 'delivered')
                            <div class="col-6 text-right">
                                <a href="javascript:" class="btn btn-outline-info mr-2" data-toggle="modal"
                                        data-target="#review-{{$order->details[0]->product_id}}">{{\App\CPU\translate('review')}}
                                </a>
                                @php($detail = $order->details[0])
                                @php($product=json_decode($detail->product_details,true))
                                <div class="modal fade rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                    id="review-{{$order->details[0]->product_id}}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">
                                                    {{$product['name']}}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('review.store')}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">

                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">{{\App\CPU\translate('rating')}}</label>
                                                        <select class="form-control" name="rating">
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">{{\App\CPU\translate('comment')}}</label>
                                                        <input name="product_id" value="{{$detail->product_id}}" hidden>
                                                        <textarea class="form-control" name="comment"></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">{{\App\CPU\translate('attachment')}}</label>
                                                        <div class="row coba"></div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('close')}}</button>
                                                    <button type="submit" class="btn btn-primary">{{\App\CPU\translate('submit')}}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                                @endif
                        @if ($order->seller_is != 'admin')
                            @if($order->order_status == 'pending' || $order->order_status == 'delivered')
                            <div class="col-md-6 d-flex justify-content-start" id="contact-seller">
                                {{-- <button class="btn btn-outline-success">
                                    Chat pemilik
                                </button> --}}
                                <button type="button" class="btn btn-outline-success mr-2" data-toggle="modal" data-target="#staticBackdrop">
                                    Chat pemilik
                                </button>
                            </div>
                            @php($seller = $order->details[0]->seller_id)
                            @php($kost = $order->details[0]->product ? $order->details[0]->product->kost->id : 0)

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
                        <div class="col-6 d-flex justify-content-start">
                            @if($order->order_status == 'pending' || $order->order_status == 'delivered')
                            {{-- <a href="{{ route('contacts') }}" target="_blank" class="btn btn-outline-success text-success">
                                    Chat Admin InRoom
                            </a> --}}
                            @php($no=\App\CPU\Helpers::get_business_settings('whatsapp'))
                            <a href="https://api.whatsapp.com/send?phone={{ $no }}" target="_blank" class="btn btn-outline-success text-success">
                                    Chat Admin InRoom
                            </a>
                            @endif
                        </div>
                        @endif


                        @if($order->order_status == 'processing')
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="mr-2 btn btn-outline-danger" data-toggle="modal" data-target="#batalkanCancel{{ $order->id }}">
                                Cancel Booking
                            </button>
                            <a href="{{ route('checkout-payment', ['order_id' => $order->id]) }}" class="btn btn-success">
                                Bayar sekarang
                            </a>
                        </div>
                        <div class="col-12 d-flex justify-content-end mb-4 pr-3">

                            <!-- Modal -->
                            <div class="modal fade" id="batalkanCancel{{ $order->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Pilih alasan pembatalan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <select id="alasanCancel{{ $order->id }}" class="custom-select custom-select-lg mb-3" name="alasan">
                                            <option value="">-- Pilih alasan pembatalan --</option>
                                            <option value="Sudah menemukan kamar lain">Sudah menemukan kamar lain</option>
                                            <option value="Ingin merubah bookingan">Ingin merubah bookingan</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="cancel({{ $order->id }})">Batalkan</button>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    {{-- {{ dd($order->details->product->kost->id) }} --}}
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
        function getTimeRemaining(endtime) {
        var t = Date.parse(endtime) - Date.parse(new Date());
        var seconds = Math.floor((t / 1000) % 60);
        var minutes = Math.floor((t / 1000 / 60) % 60);
        var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
        var days = Math.floor(t / (1000 * 60 * 60 * 24));
        return {
            'total': t,
            'days': days,
            'hours': hours,
            'minutes': minutes,
            'seconds': seconds
        };
        }

        function initializeClock(id, endtime) {
            var clock = document.getElementById(id);
            var daysSpan = clock.querySelector('.days');
            var hoursSpan = clock.querySelector('.hours');
            var minutesSpan = clock.querySelector('.minutes');
            var secondsSpan = clock.querySelector('.seconds');

            function updateClock() {
                var t = getTimeRemaining(endtime);

                daysSpan.innerHTML = t.days;
                hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
                minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
                secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

                if (t.total <= 0) {
                clearInterval(timeinterval);
                }
            }

            updateClock();
            var timeinterval = setInterval(updateClock, 1000);
        }
        function getTime(id){
            var dated =  new Date($('#count' + id).val());
            var now = new Date()
            var deadline = new Date(Date.parse(new Date()) + (dated.getTime() - now.getTime()));
            initializeClock('clockdiv' + id, deadline);
        }

        function batalkan(val){
            var alasan = $('#alasan' + val).val();
            if(alasan == ''){
                toastr.warning('{{\App\CPU\translate('Mohon pilih alasan pembatalan')}}!!');
            }else{
                Swal.fire({
                title: '{{\App\CPU\translate('Apa_anda_yakin_ingin_membatalkan')}}?',
                // text: "{{\App\CPU\translate('Pastikan_anda_telah_melihat_profil_penyewa')}}!",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'danger',
                confirmButtonText: '{{\App\CPU\translate('Batalkan')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('order-cancel-user')}}",
                        method: 'POST',
                        data: {
                            "id": val,
                            "order_status": 'canceled',
                            'alasan': alasan
                        },
                        success: function (data) {
                            console.log(data);
                            if (data.success == 0) {
                                toastr.success('{{\App\CPU\translate('Booking sudah dibayar')}} !!');
                                location.reload();
                            } else {
                                toastr.success('{{\App\CPU\translate('Booking berhasil ditolak')}}!');
                                location.reload();
                            }
                        }
                    });
                }
            })
            }
        }

        function cancel(val){
            var alasan = $('#alasanCancel' + val).val();
            if(alasan == ''){
                toastr.warning('{{\App\CPU\translate('Mohon pilih alasan pembatalan')}}!!');
            }else{
                Swal.fire({
                title: '{{\App\CPU\translate('Apa_anda_yakin_ingin_membatalkan')}}?',
                // text: "{{\App\CPU\translate('Pastikan_anda_telah_melihat_profil_penyewa')}}!",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'danger',
                confirmButtonText: '{{\App\CPU\translate('Batalkan')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('order-cancel')}}",
                        method: 'POST',
                        data: {
                            "id": val,
                            "order_status": 'canceled',
                            'alasan': alasan
                        },
                        success: function (data) {
                            console.log(data);
                            if (data.success == 0) {
                                toastr.success('{{\App\CPU\translate('Booking sudah dibayar')}} !!');
                                location.reload();
                            } else {
                                toastr.success('{{\App\CPU\translate('Booking berhasil ditolak')}}!');
                                location.reload();
                            }
                        }
                    });
                }
            })
            }
        }

        $(document).ready(function(){
            var ids = $('input[class=id]');
            var id = []
            ids.each(function(){
                var val = $(this).val();
                // array.push(id, val)
                console.log('id', val)
                getTime(val);
            })
        })


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
