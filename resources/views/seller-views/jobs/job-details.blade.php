@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('Applied Details'))

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
                                href="{{route('seller.jobs.list')}}">{{\App\CPU\translate('Bookings')}}</a>
                            </li>
                            <li class="breadcrumb-item active"
                                aria-current="page">{{\App\CPU\translate('Appiled')}} {{\App\CPU\translate('details')}} </li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate('Applied')}} #{{$order['id']}}</h1>

                        @if($order['job_status']=='applied')
                            <span class="badge badge-soft-info ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-info text"></span>Lamaran masuk
                            </span>
                        @elseif($order['job_status']=='viewed')
                            <span class="badge badge-soft-warning ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-warning"></span>Dilihat
                            </span>
                        @elseif($order['job_status']=='tolak' || $order['job_status']=='out_for_delivery')
                            <span class="badge badge-soft-danger ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-danger"></span>ditolak
                            </span>
                        @elseif($order['job_status']=='terima' || $order['job_status']=='confirmed')
                            <span class="badge badge-soft-success ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-success"></span>diterima
                            </span>
                        @else
                            <span class="badge badge-soft-danger ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-danger"></span>{{str_replace('_',' ',$order['job_status'])}}
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
                    {{-- <div class="row mb-3">
                        <div class="col-md-6 mt-2">
                            <a class="text-body mr-3" target="_blank"
                               href={{route('seller.jobs.generate-invoice',[$order['id']])}}>
                                <i class="tio-print mr-1"></i> {{\App\CPU\translate('Print')}} {{\App\CPU\translate('invoice')}}
                            </a>
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-6">
                            {{-- <div class="hs-unfold float-right">
                                <div class="dropdown">
                                    <select name="job_status" onchange="job_status(this.value)"
                                            class="status form-control"
                                            data-id="{{$order['id']}}">

                                        <option
                                            value="pending" {{$order->job_status == 'applied'?'selected':''}} > {{\App\CPU\translate('lamaran_masuk')}}</option>
                                        <option
                                            value="confirmed" {{$order->job_status == 'viewed'?'selected':''}} > {{\App\CPU\translate('dilihat')}}</option>
                                        <option
                                            value="processing" {{$order->job_status == 'processing'?'selected':''}} >{{\App\CPU\translate('Processing')}} </option>
                                        <option class="text-capitalize"
                                                value="out_for_delivery" {{$order->job_status == 'out_for_delivery'?'selected':''}} >{{\App\CPU\translate('out_for_delivery')}} </option>
                                        <option
                                            value="delivered" {{$order->job_status == 'delivered'?'selected':''}} >{{\App\CPU\translate('Delivered')}} </option>
                                        <option
                                            value="returned" {{$order->job_status == 'returned'?'selected':''}} > {{\App\CPU\translate('Returned')}}</option>
                                        <option
                                            value="failed" {{$order->job_status == 'failed'?'selected':''}} >{{\App\CPU\translate('Failed')}} </option>
                                        <option
                                            value="canceled" {{$order->job_status == 'canceled'?'selected':''}} >{{\App\CPU\translate('Canceled')}} </option>
                                    </select>
                                </div>
                            </div> --}}
                            {{-- <div class="hs-unfold float-right pr-2">
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
                            </div> --}}
                        </div>
                    </div>
                    <!-- End Unfold -->
                </div>
            </div>
        </div>
        {{-- {{ dd($order->customer) }} --}}

        <!-- End Page Header -->
        @php($detail = json_decode($order->job))
        @php($sewa = $order->customer)
        @php($district = strtolower($detail->district))
        @php($city = strtolower($detail->city))
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
                                    {{\App\CPU\translate('Applied')}} {{\App\CPU\translate('details')}}
                                </h4>
                            </div>

                            <div class="col-12">
                                <h2 class="mt-2 title-kos mt-3">{{ $detail->name }} di {{ $detail->company_name }}</h2>
                                <span class="subtitle capitalize">
                                    {{ $district }}, {{ $city }}
                                </span>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="title-sub w-100 d-block mt-2">
                                    <h4>Status pekerjaan:</h4>
                                </div>
                                <div class="row w-100">
                                    <div class="col-md-8">
                                        <div class="status-kos mt-2 mb-3">
                                            <span>
                                                {{ $detail->status_employe }}
                                            </span>
                                        </div>
                                        <span class="price">{{\App\CPU\Helpers::currency_converter($detail->gaji)}}  <span class="month">/Bulan</span></span>
                                    </div>
                                    <div class="col-md-4">
                                        <img onerror="this.src='{{asset('assets/back-end/img/400x400/img2.jpg')}}'"
                                        src="{{asset('storage/jobs')}}/{{$detail->logo}}"
                                        alt="" style="height: 98px; border-radius: 5px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body">
                        {{-- <div class="col-12 user-section d-flex py-3">
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
                        <hr> --}}
                        {{-- <div class="col-12 py-3">
                            <div class="title-sub w-100 d-block mt-2">
                                <h4>Kelengkapan dokumen persyaratan</h4>
                            </div>
                            <div class="content">
                                <div class="ktp text-center">
                                    <img onerror="this.src='{{asset('assets/back-end/img/400x400/img2.jpg')}}'" src="{{ asset('storage/ktp').'/'.$sewa->ktp }}" alt="">
                                </div>
                            </div>
                        </div> --}}
                        <hr>
                        <hr>
                        <div class="col-12 py-3">
                            <i class="fa fa-graduation-cap" style="font-size: 30px; color: #000;" class="mb-3"></i>
                            <h5 style="capitalize">Kompetensi kandidat:</h5>
                            <div class="row ml-1 my-2">
                                <div class="col-6">
                                    <span>Pendidikan terakhir :</span>
                                </div>
                                <div class="col-6 text-right">
                                    <span class="text-uppercase" style="font-weight: 700;">
                                        {{ $order->pendidikan }}
                                    </span>
                                </div>
                            </div>
                            <div class="row ml-1 mb-2">
                                <div class="col-6">
                                    <span>Keahlian :</span>
                                </div>
                                <div class="col-6 text-right">
                                    <span class="capitalize" style="font-weight: 700;">
                                        {{ $order->keahlian }}
                                    </span>
                                </div>
                            </div>
                            <div class="row ml-1 mb-2">
                                <div class="col-6">
                                    <span>Pengalaman kerja :</span>
                                </div>
                                <div class="col-6 text-right">
                                    <span class="capitalize" style="font-weight: 5Psi00;">
                                        {{ $order->experience }}
                                    </span>
                                </div>
                            </div>
                            <div class="row ml-1 mb-2">
                                <div class="col-6">
                                    <span>Penghasilan terakhir :</span>
                                </div>
                                <div class="col-6 text-right">
                                    <span class="capitalize" style="font-weight: 700;">
                                        {{\App\CPU\Helpers::currency_converter($order->penghasilan)}}
                                    </span>
                                </div>
                            </div>
                            <div class="row ml-1 mb-2">
                                <div class="col-6">
                                    <span>Gaji yang diharapkan :</span>
                                </div>
                                <div class="col-6 text-right">
                                    <span class="capitalize" style="font-size: 18px;font-weight: 700;">
                                        {{\App\CPU\Helpers::currency_converter($order->gaji)}}
                                    </span>
                                </div>
                            </div>
                            @if ($order->cv)
                            <div class="row ml-1 mb-2">
                                <div class="col-6">
                                    <span>Curriculum vitae :</span>
                                </div>
                                <div class="col-6 text-right">
                                    <a class="capitalize" href="{{ asset('storage/cv'.'/'.$order->cv) }}" target="_blank" style="font-weight: 500;">
                                        <i class="fa fa-eye"></i> Lihat
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                        <hr>
                        <div class="col-12 py-3">
                            <i class="fa fa-sticky-note mb-3" aria-hidden="true" style="    font-size: 27px;
                            color: #000;"></i>
                            <h5 style="capitalize">Data diri kandidat:</h5>
                            <div class="row ml-1 my-2">
                                <div class="col-6">
                                    <span>Nama :</span>
                                </div>
                                <div class="col-6 text-right">
                                    <span class="text-uppercase" style="font-weight: 700;">
                                        {{ $order->name }}
                                    </span>
                                </div>
                            </div>
                            <div class="row ml-1 mb-2">
                                <div class="col-6">
                                    <span>Phone :</span>
                                </div>
                                <div class="col-6 text-right">
                                    <span class="capitalize" style="font-weight: 700;">
                                        {{ $order->phone }}
                                    </span>
                                </div>
                            </div>
                            <div class="row ml-1 mb-2">
                                <div class="col-6">
                                    <span>Email :</span>
                                </div>
                                <div class="col-6 text-right">
                                    <span class="capitalize" style="font-weight: 700;">
                                        {{ $order->email }}
                                    </span>
                                </div>
                            </div>
                            <div class="row ml-1 mb-2">
                                <div class="col-6">
                                    <span>Alamat :</span>
                                </div>
                                <div class="col-6 text-right">
                                    <span class="capitalize" style="font-weight: 700;">
                                        {{ $order->address }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr>
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
                        @if($order['job_status']=='viewed')
                        <span class="badge badge-soft-warning text-capitalize" style="font-size: 14px;">
                            {{ \App\CPU\translate('Dilihat') }}
                        </span>
                        @elseif($order['job_status']=='tolak')
                            <span class="badge badge-soft-danger ml-sm-3 text-capitalize" style="font-size: 14px;">
                            <span class="legend-indicator bg-danger"></span>
                            {{ \App\CPU\translate('Ditolak') }}
                            </span>
                        @elseif($order['job_status']=='terima' || $order['job_status']=='out_for_delivery')
                            <span class="badge badge-soft-success text-capitalize" style="font-size: 14px;">
                                {{ \App\CPU\translate('Diterima') }}
                            </span>
                        @elseif($order['job_status']=='delivered' || $order['job_status']=='confirmed')
                            <span class="badge badge-soft-success ml-sm-3 text-capitalize" style="font-size: 14px;">
                            {{ \App\CPU\translate('terbayar') }}
                            </span>
                        @else
                            <span class="badge badge-soft-danger ml-sm-3 text-capitalize" style="font-size: 14px;">
                            {{str_replace('_',' ',$order['job_status'])}}
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
                        <h5 class="">
                            {{\App\CPU\translate('Nama')}} {{\App\CPU\translate('Kandidat')}}:
                        </h5>
                        <div class="subtitle">
                            {{ $order->customer->f_name }} {{ $order->customer->l_name }}
                        </div>
                        {{-- @php($date = Carbon\Carbon::parse($order->mulai)->isoFormat('dddd, D MMMM Y'))
                        <div class="col-12 d-flex justify-content-between mt-3 px-0">
                            <span class="capitalize">Mulai sewa</span>
                            <span>{{ App\CPU\Helpers::dateChange($date) }}</span>
                        </div> --}}
                        {{-- @if (count($order->room) > 0)
                        @if ($order->room[0]->habis != NULL)
                        @php($abis = Carbon\Carbon::parse($order->room[0]->habis)->isoFormat('dddd, D MMMM Y'))
                        <div class="col-12 d-flex justify-content-between mt-3 px-0">
                            <span class="capitalize">Habis sewa</span>
                            <span>{{ App\CPU\Helpers::dateChange($abis) }}</span>
                        </div>
                        @endif
                        @endif --}}
                        <div class="col-12 d-flex justify-content-between mt-3 px-0">
                            <span class="capitalize">Telepon</span>
                            <span>{{ $order->phone }}</span>
                        </div>
                        <div class="col-12 d-flex justify-content-between mt-3 px-0">
                            <span class="capitalize">Email</span>
                            <span>{{ $order->email }}</span>
                        </div>
                        <div class="col-12 d-flex justify-content-between mt-3 px-0">
                            <span class="capitalize">Bersedia ditempatkan</span>
                            <span>
                                @if ($order->onsite == '1')
                                    Ya
                                @else
                                    Tidak
                                @endif
                            </span>
                        </div>
                    </div>
                <!-- End Body -->
                @if ($order['job_status']=='viewed' )
                <div class="card-footer d-flex justify-content-center">
                    <div class="row w-100">
                        <div class="col-md-6">
                            <button class="btn btn-outline-secondary w-100" onclick="order_status('tolak')">
                                {{ \App\CPU\Translate('Tolak') }}
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a class="btn btn-outline-success w-100" onclick="order_status('terima')">
                                {{ \App\CPU\Translate('Terima') }}
                            </a>
                        </div>

                    </div>
                </div>
                @endif
                </div>
                <!-- End Card -->
                <!-- Modal tolak-->
                {{-- <div class="modal fade" id="tolak" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <div class="modal-body">
                                <input type="hidden" name="job_status" value="processing">
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
                        <button onclick="job_status('processing')" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                    </div>
                </div> --}}
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


        function order_status(status) {
            if(status=='terima')
            Swal.fire({
                title: '{{\App\CPU\translate('Apa anda ingin menerima kandidat ini')}}?',
                text: "{{\App\CPU\translate('Think before you proceed')}}.",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{\App\CPU\translate('Terima')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('seller.jobs.apply_status')}}",
                        method: 'POST',
                        data: {
                            "id": '{{$order['id']}}',
                            "order_status": status
                        },
                        success: function (data) {
                            if (data.success == 0) {
                                toastr.success('{{\App\CPU\translate('Order is already delivered, You can not change it')}} !!');
                                location.reload();
                            } else {
                                toastr.success('{{\App\CPU\translate('Kandidat berhasil diterima')}}!');
                                location.reload();
                            }

                        }
                    });
                }
            })
            else
            Swal.fire({
                title: '{{\App\CPU\translate('Yakin ingin menolak kandidat ini')}}?',
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{\App\CPU\translate('Tolak')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('seller.jobs.apply_status')}}",
                        method: 'POST',
                        data: {
                            "id": '{{$order['id']}}',
                            "order_status": status
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
