@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('Booked Details'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .detail-price {
            border: 1px solid #f5f5f5;
            padding: 5px;
            border-radius: 4px;
            background-color: #f5f5f5;
        }
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
                                href="{{route('admin.orders.list',['status'=>'all'])}}">{{\App\CPU\translate('Booked')}}</a>
                            </li>
                            <li class="breadcrumb-item active"
                                aria-current="page">{{\App\CPU\translate('Booking')}} {{\App\CPU\translate('details')}} </li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate('Booking')}} #{{$book['id']}}</h1>

                        @if($book['payment_status']=='paid')
                            <span class="badge badge-soft-success ml-sm-3">
                                <span class="legend-indicator bg-success"></span>{{\App\CPU\translate('Paid')}}
                            </span>
                        @else
                            <span class="badge badge-soft-danger ml-sm-3">
                                <span class="legend-indicator bg-danger"></span>{{\App\CPU\translate('Unpaid')}}
                            </span>
                        @endif

                        <span class="ml-2 ml-sm-3">
                        <i class="tio-date-range"></i> {{date('d M Y H:i:s',strtotime($book['created_at']))}}
                        </span>

                    </div>
                    <!-- End Unfold -->
                </div>
            </div>
        </div>

        <!-- End Page Header -->
        @php($detail = json_decode($book->details[0]->product_details))
        @php($sewa = $book->customer)
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
                                    {{\App\CPU\translate('Booked')}} {{\App\CPU\translate('details')}}
                                    <span
                                        class="badge badge-soft-dark rounded-circle ml-1">{{$book->details->count()}}</span>
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
                                    <h4>Properti:</h4>
                                </div>
                                <div class="row w-100">
                                    <div class="col-md-8">
                                        <div class="d-flex mb-4">
                                            <div class="status-kos mt-2">
                                                <span>
                                                    {{ $detail->kost->penghuni }}
                                                </span>
                                            </div>
                                            @if (isset($book->room[0]))
                                            <div class="status-kos w-100 d-block mt-2">
                                                <span>
                                                    @if ($book->roomDetail_id == NULL)
                                                    Kamar belum dipilih
                                                    @else
                                                    Kamar  {{ $book->room[0]->name }}
                                                    @endif
                                                </span>
                                            </div>
                                            @else
                                                <span class="badge badge-danger d-flex align-items-center text-center mt-1">Invalid room data</span>
                                            @endif
                                        </div>
                                        <span class="price">{{\App\CPU\Helpers::currency_converter($book->order_amount)}}  <span class="month">/ {{ $book->durasi }} Bulan</span></span>
                                        <div class="row detail-price mt-3 ml-2">
                                            <div class="col-12">
                                                <span class="d-block">Detail Harga:</span>
                                            </div>
                                            <div class="col-12 col-md-8 pl-4 d-flex justify-content-between">
                                                <span>Harga awal : </span> <span class="text-success"> {{ \App\CPU\Helpers::currency_converter($book->details[0]->price) }}</span>
                                            </div>
                                            <div class="col-12 col-md-8 pl-4 d-flex justify-content-between">
                                                <span>Diskon : </span> <span class="text-success"> - {{ \App\CPU\Helpers::currency_converter($book->details[0]->discount) }}</span>
                                            </div>
                                            <div class="col-12 col-md-8 pl-4 d-flex justify-content-between">
                                                <span>Tax : </span><span class="text-danger"> + {{ \App\CPU\Helpers::currency_converter($book->details[0]->tax) }}</span>
                                            </div>
                                            @if ($book->usePoin == 1)
                                                <div class="col-12 col-md-8 pl-4 d-flex justify-content-between">
                                                    <span>Poin : </span><span class="text-success"> - {{ \App\CPU\Helpers::currency_converter($book->details[0]->poin) }}</span>
                                                </div>
                                            @endif
                                            @php($deposit = json_decode($book->details[0]->product_details))
                                            @if (isset($deposit->deposit))
                                                <div class="col-12 col-md-8 pl-4 d-flex justify-content-between">
                                                    <span>Deposit : </span><span class="text-danger"> + {{ \App\CPU\Helpers::currency_converter($deposit->deposit) }}</span>
                                                </div>
                                            @endif
                                            <div class="col-12 col-md-8 pl-4 d-flex justify-content-between mt-1 pt-2" style="border-top: solid 1px #c5c5c5">
                                                <span>Pembayaran awal : </span><span class="text-danger"> {{ \App\CPU\Helpers::currency_converter($book->firstPayment) }}</span>
                                            </div>
                                        </div>
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
                            {{-- <button class="btn btn-outline-secondary px-4 my-auto" style="height: 40px;">
                                Chat
                            </button> --}}
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
                                {{ $book->jumlah_penyewa }} Penyewa
                            </span>
                        </div>
                        <hr>
                        <div class="col-12 py-3">
                            <i class="fa fa-sticky-note mb-3" aria-hidden="true" style="    font-size: 27px;
                            color: #000;"></i>
                            <h5 style="capitalize">Catatan tambahan:</h5>
                            <span style="font-weight: 700;" class="capitalize">
                                {{ $book->catatan_tambahan }}
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
                        <span class="text-capitalize">Status pembayaran</span>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body">
                        <h3 class="">
                            {{\App\CPU\translate('Waktu')}} {{\App\CPU\translate('Pemesanan')}}:
                        </h3>
                        <div class="subtitle">
                            {{date('d M Y',strtotime($book['created_at']))}}, Pukul {{ date('H:i', strtotime($book['created_at'])) }}
                        </div>
                        @php($date = Carbon\Carbon::parse($book->mulai)->isoFormat('dddd, D MMMM Y'))
                        <div class="col-12 d-flex justify-content-between mt-3 px-0">
                            <span class="capitalize">Mulai sewa</span>
                            <span>{{ App\CPU\Helpers::dateChange($date) }}</span>
                        </div>
                        @if (count($book->room) > 0)
                        @if ($book->room[0]->habis != NULL)
                        @php($abis = Carbon\Carbon::parse($book->room[0]->habis)->isoFormat('dddd, D MMMM Y'))
                        <div class="col-12 d-flex justify-content-between mt-3 px-0">
                            <span class="capitalize">Habis sewa</span>
                            <span>{{ App\CPU\Helpers::dateChange($abis) }}</span>
                        </div>
                        @endif
                        @endif
                        <div class="col-12 d-flex justify-content-between mt-3 px-0">
                            <span class="capitalize">Durasi sewa</span>
                            <span>{{ $book->durasi }} Bulan</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3>Detail pembayaran</h3>
                        <div class="detail-pembayaran">
                            @foreach ($book->booked as $b)
                                <div class="row mt-5">
                                    <div class="col-12 d-flex justify-content-between">
                                        <span class="field">Bulan ke-</span>
                                        <span class="" style="font-size: 25px;">
                                            @if ($b->bulan_ke == 0)
                                                LUNAS
                                            @else
                                                {{ $b->bulan_ke }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="col-12 d-flex justify-content-between mt-3">
                                        <span class="field">Status</span>
                                        <span class="badge pt-2 pb-1 {{ $b->payment_status == 'paid' ? 'badge-success' : 'badge-danger' }}">{{ $b->payment_status }}</span>
                                    </div>
                                    <div class="col-12 d-flex justify-content-between mt-3">
                                        <span class="field">Tanggal Bayar</span>
                                        <span class="">{{ App\CPU\Helpers::dateChange($b->created_at) }}</span>
                                    </div>
                                    <div class="col-12 d-flex justify-content-between mt-3">
                                        <span class="field">Jumlah bayar</span>
                                        <span class="">{{ App\CPU\Helpers::currency_converter($b->current_payment) }}</span>
                                    </div>
                                    <div class="col-12 d-flex justify-content-between mt-3">
                                        <span class="field">Tanggal Bayar berikutnya</span>
                                        <span class="">
                                            @if ($b->bulan_ke == 0)
                                                Lunas
                                            @else
                                                {{ App\CPU\Helpers::dateChange($b->next_payment_date) }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="col-12 d-flex justify-content-between mt-3">
                                        <span class="field">Nominal pembayaran berikutnya</span>
                                        <span class="badge badge-warning pt-2 pb-1">{{ App\CPU\Helpers::currency_converter(($b->next_payment)) }}</span>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @endforeach
                        </div>
                    </div>
                <!-- End Body -->
                @if ($book['struk'] != NULL && $book['order_status'] == 'delivered')
                <a onclick="cancel('canceled')" class="btn btn-danger w-100">
                    {{ \App\CPU\Translate('Batalkan') }}
                </a>
                @endif
                @if ($book['order_status']=='pending' )
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
                        @php($rooms = $book->details[0]->product->room)
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
                            "id": '{{$book['id']}}',
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
                            "id": '{{$book['id']}}',
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
