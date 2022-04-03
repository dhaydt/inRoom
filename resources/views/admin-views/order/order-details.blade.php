@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Order Details'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .ktp img{
            max-width: 300px;
            height: auto;
        }
        .sellerName {
            height: fit-content;
            margin-top: 10px;
            margin-left: 10px;
            font-size: 16px;
            border-radius: 25px;
            text-align: center;
            padding-top: 10px;
        }
        .title-kos{
            font-size: 24px;
            font-weight: 700;
            line-height: 32px;
            text-transform: capitalize;
        }
        .status-kos span {
            border: 1px solid #d1d1d1;
            text-transform: capitalize;
            border-radius: 5px;
            padding: 5px;
            margin-right: 10px;
            font-weight: 600;
        }
        .room-status {
            text-transform: capitalize;
            font-weight: 600;
            margin: 15px 0 15px 0;
            color: #454545;
        }
        .price{
            font-weight: 700;
            font-size: 18px;
        }
        .price span {
            font-weight: 500;
        }
        .card-confirm{
            position: sticky;
            top: 70px;
        }
        .alasan{
            font-size: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header d-print-none p-3" style="background: white">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="{{route('admin.orders.list',['status'=>'all'])}}">{{\App\CPU\translate('Bookings')}}</a>
                            </li>
                            <li class="breadcrumb-item active"
                                aria-current="page">{{\App\CPU\translate('Booking')}} {{\App\CPU\translate('details')}} </li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate('Booking')}} #{{$order['id']}}</h1>

                        @if($order['payment_status']=='paid')
                            <span class="badge badge-soft-success ml-sm-3">
                                <span class="legend-indicator bg-success"></span>{{\App\CPU\translate('Paid')}}
                            </span>
                        @else
                            <span class="badge badge-soft-danger ml-sm-3">
                                <span class="legend-indicator bg-danger"></span>{{\App\CPU\translate('Unpaid')}}
                            </span>
                        @endif

                        @if($order['order_status']=='pending')
                            <span class="badge badge-soft-info ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-info text"></span>need confirmation
                            </span>
                        @elseif($order['order_status']=='failed')
                            <span class="badge badge-danger ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-info"></span>Gagal
                            </span>
                        @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                            <span class="badge badge-soft-warning ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-warning"></span>tunggu pembayaran
                            </span>
                        @elseif($order['order_status']=='delivered' || $order['order_status']=='confirmed')
                            <span class="badge badge-soft-success ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-success"></span>Terbayar
                            </span>
                        @else
                            <span class="badge badge-soft-danger ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-danger"></span>{{str_replace('_',' ',$order['order_status'])}}
                            </span>
                        @endif
                        <span class="ml-2 ml-sm-3">
                        <i class="tio-date-range"></i> {{date('d M Y H:i:s',strtotime($order['created_at']))}}
                        </span>

                        @if(\App\CPU\Helpers::get_business_settings('order_verification'))
                            <span class="ml-2 ml-sm-3">
                                <b>
                                    {{\App\CPU\translate('Booking_verification_code')}} : {{$order['verification_code']}}
                                </b>
                            </span>
                        @endif
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mt-2">
                            <a class="text-body mr-3" target="_blank"
                               href={{route('admin.orders.generate-invoice',[$order['id']])}}>
                                <i class="tio-print mr-1"></i> {{\App\CPU\translate('Print')}} {{\App\CPU\translate('invoice')}}
                            </a>
                        </div>
                        @if ($order->order_status == 'pending')
                        <div class="col-md-6 mt-2 text-right">
                            <a class="text-body mr-3 btn btn-outline-secondary"
                            href='javascript:' data-toggle="modal" data-target="#upload">
                                <i class="tio-print mr-1"></i> Upload Bukti Transfer
                            </a>
                        </div>
                        @endif
                        <!-- Modal -->
                            <div class="modal fade" id="upload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Upload bukti transfer manual</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <form action="{{ route('admin.orders.manual-payment') }}" method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        @csrf
                                        @php($rooms = $order->details[0]->product->room)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="hidden" name="id" value="{{ $order->id }}">
                                                <input type="hidden" name="order_status" value="delivered">
                                                <select id="roomsd" class="custom-select custom-select-lg mb-3" name="no_kamar">
                                                    <option value="">Pilih nomor kamar</option>
                                                    <option value="id{{ $rooms[0]->room_id }}">Pilih ditempat</option>
                                                    @foreach ($rooms as $r)
                                                    @if ($r->available == 1)
                                                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="name">{{ \App\CPU\translate('Pilih bukti transfer')}}</label>
                                                <br>
                                                <div class="custom-file" style="text-align: left">
                                                    <input type="file" name="image" id="mbimageFileUploader"
                                                        class="custom-file-input"
                                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                    <label class="custom-file-label"
                                                        for="mbimageFileUploader">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <center>
                                                    <img
                                                        style="max-width: 250px;border: 1px solid; border-radius: 10px; max-height:200px;"
                                                        id="mbImageviewer"
                                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                        src="{{asset('storage/struk'.'/'.$order->struk)}}"
                                                        alt="banner image"/>
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                    </form>
                                    </div>
                                </div>
                                </div>
                            </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mt-4">
                            <label class="badge badge-info">{{\App\CPU\translate('linked_orders')}}
                                : {{$linked_orders->count()}}</label><br>
                            @foreach($linked_orders as $linked)
                                <a href="{{route('admin.orders.details',[$linked['id']])}}" class="btn btn-secondary">{{\App\CPU\translate('ID')}}
                                    :{{$linked['id']}}</a>
                            @endforeach
                        </div>

                        <div class="col-6">
                            <div class="hs-unfold float-right">
                                <div class="dropdown">
                                    <select name="order_status" onchange="order_status(this.value)"
                                            class="status form-control"
                                            data-id="{{$order['id']}}">

                                        <option
                                            value="pending" {{$order->order_status == 'pending'?'selected':''}} > {{\App\CPU\translate('Pending')}}</option>
                                        <option
                                            value="confirmed" {{$order->order_status == 'confirmed'?'selected':''}} > {{\App\CPU\translate('Confirmed')}}</option>
                                        <option
                                            value="processing" {{$order->order_status == 'processing'?'selected':''}} >{{\App\CPU\translate('Processing')}} </option>
                                        <option class="text-capitalize"
                                                value="out_for_delivery" {{$order->order_status == 'out_for_delivery'?'selected':''}} >{{\App\CPU\translate('out_for_delivery')}} </option>
                                        <option
                                            value="delivered" {{$order->order_status == 'delivered'?'selected':''}} >{{\App\CPU\translate('Delivered')}} </option>
                                        <option
                                            value="returned" {{$order->order_status == 'returned'?'selected':''}} > {{\App\CPU\translate('Returned')}}</option>
                                        <option
                                            value="failed" {{$order->order_status == 'failed'?'selected':''}} >{{\App\CPU\translate('Failed')}} </option>
                                        <option
                                            value="canceled" {{$order->order_status == 'canceled'?'selected':''}} >{{\App\CPU\translate('Canceled')}} </option>
                                    </select>
                                </div>
                            </div>
                            <div class="hs-unfold float-right pr-2">
                                <div class="dropdown">
                                    <select name="payment_status" class="payment_status form-control"
                                            data-id="{{$order['id']}}">

                                        <option
                                            onclick="route_alert('{{route('admin.orders.payment-status',['id'=>$order['id'],'payment_status'=>'paid'])}}','Change status to paid ?')"
                                            href="javascript:"
                                            value="paid" {{$order->payment_status == 'paid'?'selected':''}} >
                                            {{\App\CPU\translate('Paid')}}
                                        </option>
                                        <option value="unpaid" {{$order->payment_status == 'unpaid'?'selected':''}} >
                                            {{\App\CPU\translate('Unpaid')}}
                                        </option>

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Unfold -->
                </div>
            </div>
        </div>

        <!-- End Page Header -->
        @php($detail = json_decode($order->details[0]->product_details))
        @php($sewa = $order->customer)
        @php($district = strtolower($detail->kost->district))
        @php($city = strtolower($detail->kost->city))
        <div class="row" id="printableArea">
            {{-- {{ dd($order) } --}}
            <div class="col-lg-8 mb-3 mb-lg-0">
                <!-- Card -->
                <div class="card mb-3 mb-lg-5">
                    <!-- Header -->
                    <div class="card-header" style="display: block!important;">
                        <div class="row">
                            <div class="col-12 pb-2 border-bottom">
                                <h4 class="card-header-title">
                                    {{\App\CPU\translate('Booking')}} {{\App\CPU\translate('details')}}
                                    <span
                                        class="badge badge-soft-dark rounded-circle ml-1">{{$order->details->count()}}</span>
                                </h4>
                            </div>

                            <div class="col-12">
                                <h2 class="mt-2 title-kos mt-3">{{ $detail->kost->name }} {{ $detail->type }} {{ $district }} {{ $city }}</h2>
                                <span class="subtitle capitalize">
                                    {{ $district }}, {{ $city }}
                                </span>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="title-sub w-100 d-block mt-2">
                                    <h4>Properti yang dipesan:</h4>
                                </div>
                                <div class="row w-100">
                                    <div class="col-md-8">
                                        <div class="status-kos mt-2">
                                            <span>
                                                {{ $detail->kost->penghuni }}
                                            </span>
                                            @php($stock = $order->details[0]->product->current_stock)
                                            @if ($stock <= 3 && $stock != 0)
                                            <span>
                                                {{\App\CPU\translate('Sisa')}} {{ $stock }} {{\App\CPU\translate('kamar')}}
                                            </span>
                                            @elseif ($stock == 0)
                                            <span>
                                                Kamar Habis
                                            </span>
                                            @else
                                            <span>
                                                Ada {{ $stock }} kamar
                                            </span>
                                            @endif
                                        </div>
                                        <span class="room-status w-100 d-block">
                                            @if ($order->roomDetail_id == NULL)
                                                Kamar belum dikonfirmasi
                                            @elseif ($order->roomDetail_id == 'ditempat')
                                                Pilih kamar ditempat
                                            @else
                                                Kamar  {{ $order->room[0]->name }}
                                            @endif
                                        </span>
                                        <span class="price">{{\App\CPU\Helpers::currency_converter($order->details[0]->price)}}  <span class="month">/Bulan</span></span>
                                    </div>
                                    <div class="col-md-4">
                                        <img onerror="this.src='{{asset('assets/back-end/img/400x400/img2.jpg')}}'"
                                        src="{{asset('storage/product')}}/{{json_decode($detail->images)[0]}}"
                                        alt="" style="height: 98px; border-radius: 5px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body">
                        <div class="col-12 user-section d-flex py-3">
                            <img src="{{ asset('assets/back-end/img/admin.jpg') }}" alt="" class="user mr-4" style="height: 56px">
                            <div class="d-flex flex-column mr-5">
                                <h3 class="mb-0">{{ $sewa->f_name }} {{ $sewa->l_name }}</h3>
                                <span class="phone">
                                    +62{{ $sewa->phone }}
                                </span>
                            </div>
                            <button class="btn btn-outline-secondary px-4 my-auto" style="height: 40px;">
                                Chat
                            </button>
                        </div>
                        <hr>
                        <div class="col-12 py-3">
                            <div class="title-sub w-100 d-block mt-2">
                                <h4>Kelengkapan dokumen persyaratan</h4>
                            </div>
                            <div class="content">
                                <div class="ktp text-center">
                                    <img onerror="this.src='{{asset('assets/back-end/img/400x400/img2.jpg')}}'" src="{{ asset('storage/ktp').'/'.$sewa->ktp }}" alt="">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-12 py-3">
                            <img src="{{ asset('assets/back-end/img/keyhand.png') }}" alt="" style="height: 30px;" class="mb-3">
                            <h5 style="capitalize">Jumlah Penyewa:</h5>
                            <span style="font-weight: 700;">
                                {{ $order->jumlah_penyewa }} Penyewa
                            </span>
                        </div>
                        <hr>
                        <div class="col-12 py-3">
                            <i class="fa fa-sticky-note mb-3" aria-hidden="true" style="    font-size: 27px;
                            color: #000;"></i>
                            <h5 style="capitalize">Catatan tambahan:</h5>
                            <span style="font-weight: 700;" class="capitalize">
                                {{ $order->catatan_tambahan }}
                            </span>
                        </div>
                        <hr>
                        <div class="col-12 py-3">
                            <img src="{{ asset('assets/back-end/img/user.png') }}" alt="" style="height: 30px;" class="mb-3">
                            <h5 style="capitalize">Profil Penyewa:</h5>
                            <span class="capitalize d-block pb-3">
                                {{ $sewa->kelamin }}
                            </span>
                            <span class="capitalize d-block pb-3">
                                {{ $sewa->status_pernikahan }}
                            </span>
                            <span class="capitalize d-block pb-3">
                                {{ $sewa->pekerjaan }} - {{ $sewa->kampus ? $sewa->kampus : $sewa->tempat_kerja }}
                            </span>
                            <span class="capitalize">
                                {{ $sewa->email }}
                            </span>
                        </div>
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>

            <div class="col-lg-4">
                <!-- Card -->
                <div class="card card-confirm">
                    <!-- Header -->
                    <div class="card-header px-2">
                        @if($order['order_status']=='pending')
                        <span class="badge badge-soft-warning text-capitalize" style="font-size: 14px;">
                            {{ \App\CPU\translate('butuh_konfirmasi') }}
                        </span>
                        @elseif($order['order_status']=='failed')
                            <span class="badge badge-danger ml-sm-3 text-capitalize" style="font-size: 14px;">
                            <span class="legend-indicator bg-info"></span>
                            {{ \App\CPU\translate('gagal') }}
                            </span>
                        @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                            <span class="badge badge-soft-success text-capitalize" style="font-size: 14px;">
                                {{ \App\CPU\translate('tunggu_pembayaran') }}
                            </span>
                        @elseif($order['order_status']=='delivered' || $order['order_status']=='confirmed')
                            <span class="badge badge-soft-success ml-sm-3 text-capitalize" style="font-size: 14px;">
                            {{ \App\CPU\translate('terbayar') }}
                            </span>
                        @else
                            <span class="badge badge-soft-danger ml-sm-3 text-capitalize" style="font-size: 14px;">
                            {{str_replace('_',' ',$order['order_status'])}}
                            </span>
                        @endif
                        <span class="alasan">
                            @if ($order['alasan_admin'] != 'NULL')
                                {{ $order['alasan_admin'] }}
                            @endif
                            @if ($order->alasan_user != 'NULL')
                                {{ $order->alasan_user }}
                            @endif
                        </span>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body">
                        <h3 class="">
                            {{\App\CPU\translate('Waktu')}} {{\App\CPU\translate('Pemesanan')}}:
                        </h3>
                        <div class="subtitle">
                            {{date('d M Y',strtotime($order['created_at']))}}, Pukul {{ date('H:m', strtotime($order['created_at'])) }}
                        </div>
                        @php($date = Carbon\Carbon::parse($order->mulai)->isoFormat('dddd, D MMMM Y'))
                        <div class="col-12 d-flex justify-content-between mt-3 px-0">
                            <span class="capitalize">Mulai sewa</span>
                            <span>{{ App\CPU\Helpers::dateChange($date) }}</span>
                        </div>
                        @if (count($order->room) > 0)
                        @if ($order->room[0]->habis != NULL)
                        @php($abis = Carbon\Carbon::parse($order->room[0]->habis)->isoFormat('dddd, D MMMM Y'))
                        <div class="col-12 d-flex justify-content-between mt-3 px-0">
                            <span class="capitalize">Habis sewa</span>
                            <span>{{ App\CPU\Helpers::dateChange($abis) }}</span>
                        </div>
                        @endif
                        @endif
                        <div class="col-12 d-flex justify-content-between mt-3 px-0">
                            <span class="capitalize">Durasi sewa</span>
                            <span>{{ $order->durasi }} Bulan</span>
                        </div>
                    </div>
                <!-- End Body -->
                @if ($order['order_status']=='pending' )
                <div class="card-footer d-flex justify-content-center">
                    <div class="row w-100">
                        <div class="col-md-6">
                            <button class="btn btn-outline-secondary w-100" data-toggle="modal" data-target="#tolak">
                                {{ \App\CPU\Translate('Tolak') }}
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a class="btn w-100 @if ($stock == 0) disabled btn-danger @else btn-success @endif" type="button" data-toggle="modal" data-target="#exampleModal">
                            @if ($stock == 0)
                            {{ \App\CPU\Translate('Kamar_habis') }}
                            @else
                            {{ \App\CPU\Translate('Terima') }}
                        @endif
                            </a>
                            {{-- <a onclick="order_status('processing')" class="btn btn-success w-100">
                                {{ \App\CPU\Translate('Terima') }}
                            </a> --}}
                        </div>

                    </div>
                </div>
                @endif
                </div>
                <!-- End Card -->
                <!-- Modal tolak-->
                <div class="modal fade" id="tolak" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pilih alasan penolakan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <select id="alasan" class="custom-select custom-select-lg mb-3" name="alasan">
                                <option value="">-- Pilih alasan penolakan --</option>
                                <option value="Sudah dibooking">Sudah dibooking</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button onclick="tolak()" class="btn btn-primary">Tolak</button>
                        </div>
                    </div>
                    </div>
                </div>
                <!-- Modal terima-->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pilih penempatan kamar untuk penyewa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        @php($rooms = $order->details[0]->product->room)
                        {{-- {{ dd($rooms) }} --}}
                        <div class="modal-body">
                                <input type="hidden" name="order_status" value="processing">
                                <select id="rooms" class="custom-select custom-select-lg mb-3" name="no_kamar">
                                    <option selected>Pilih nomor kamar</option>
                                    <option value="id{{ $rooms[0]->room_id }}">Pilih ditempat</option>
                                    @foreach ($rooms as $r)
                                    @if ($r->available == 1)
                                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button onclick="order_status('processing')" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection


@push('script_2')
    <script>
        function mbimagereadURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#mbImageviewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#mbimageFileUploader").change(function () {
            mbimagereadURL(this);
        });


        $(document).on('change', '.payment_status', function () {
            var id = $(this).attr("data-id");
            var value = $(this).val();
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure Change this')}}?',
                text: "{{\App\CPU\translate('You will not be able to revert this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{\App\CPU\translate('Yes, Change it')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.orders.payment-status')}}",
                        method: 'POST',
                        data: {
                            "id": id,
                            "payment_status": value
                        },
                        success: function (data) {
                            toastr.success('{{\App\CPU\translate('Status Change successfully')}}');
                            location.reload();
                        }
                    });
                }
            })
        });

        function tolak(){
            var alasan = $('#alasan').val();
            if(alasan == ''){
                toastr.warning('{{\App\CPU\translate('Mohon pilih alasan penolakan')}}!!');
            }else{
                Swal.fire({
                title: '{{\App\CPU\translate('Apa_anda_yakin_ingin_menolak')}}?',
                // text: "{{\App\CPU\translate('Pastikan_anda_telah_melihat_profil_penyewa')}}!",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'danger',
                confirmButtonText: '{{\App\CPU\translate('Tolak')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.orders.status')}}",
                        method: 'POST',
                        data: {
                            "id": '{{$order['id']}}',
                            "order_status": 'cancelled',
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
        function order_status(status) {
            var room = $('#rooms').val()
            var roomd = $('#roomsd').val()
            if(room){
                var room = room;
            }else{
                var room = roomd;
            }
            Swal.fire({
                title: '{{\App\CPU\translate('Apa_anda_yakin_ingin_menerima')}}?',
                text: "{{\App\CPU\translate('Pastikan_anda_telah_melihat_profil_penyewa')}}!",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{\App\CPU\translate('Ya, Terima_penyewa')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.orders.status')}}",
                        method: 'POST',
                        data: {
                            "id": '{{$order['id']}}',
                            "order_status": status,
                            'no_kamar': room
                        },
                        success: function (data) {
                            if (data.success == 0) {
                                toastr.success('{{\App\CPU\translate('Order is already delivered, You can not change it')}} !!');
                                location.reload();
                            } else {
                                toastr.success('{{\App\CPU\translate('Status Change successfully')}}!');
                                location.reload();
                            }
                        }
                    });
                }
            })
        }
    </script>
@endpush
