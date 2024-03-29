@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Product Preview'))

@push('css_or_js')
<style>
    .checkbox-color label {
        width: 2.25rem;
        height: 2.25rem;
        float: left;
        padding: 0.375rem;
        margin-right: 0.375rem;
        display: block;
        font-size: 0.875rem;
        text-align: center;
        opacity: 0.7;
        border: 2px solid #d3d3d3;
        border-radius: 50%;
        -webkit-transition: all 0.3s ease;
        -moz-transition: all 0.3s ease;
        -o-transition: all 0.3s ease;
        -ms-transition: all 0.3s ease;
        transition: all 0.3s ease;
        transform: scale(0.95);
    }
</style>
@endpush

@section('content')
{{-- {{ dd($order) }} --}}
<div class="content container-fluid" style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex-between row mx-1">
            <div>
                <h1 class="page-header-title">{{$product['name']}}</h1>
            </div>
            <div class="row">
                <div class="col-12 flex-start">
                    <div class="{{Session::get('direction') === " rtl" ? 'ml-3' : 'mr-3' }}">
                        <a href="{{url()->previous()}}" class="btn btn-primary float-right">
                            <i class="tio-back-ui"></i> {{\App\CPU\translate('Back')}}
                        </a>
                    </div>
                    <div>
                        <a href="{{route('product',$product['slug'])}}" class="btn btn-primary " target="_blank"><i
                                class="tio-globe"></i> {{ \App\CPU\translate('View') }} {{ \App\CPU\translate('from') }}
                            {{ \App\CPU\translate('Website') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if($product['added_by'] == 'seller' && ($product['request_status'] == 0 || $product['request_status'] == 1))
        <div class="row">
            <div class="{{Session::get('direction') === " rtl" ? 'mr-3' : 'ml-3' }}">
                @if($product['request_status'] == 0)
                <a href="{{route('admin.product.approve-status', ['id'=>$product['id']])}}"
                    class="btn btn-secondary float-right">
                    {{\App\CPU\translate('Approve')}}
                </a>
                @endif
            </div>
            <div class="{{Session::get('direction') === " rtl" ? 'mr-1' : 'ml-1' }}">
                <button class="btn btn-warning float-right" data-toggle="modal" data-target="#publishNoteModal">
                    {{\App\CPU\translate('deny')}}
                </button>
                <!-- Modal -->
                <div class="modal fade" id="publishNoteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{ \App\CPU\translate('denied_note') }}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form class="form-group" action="{{ route('admin.product.deny', ['id'=>$product['id']]) }}"
                                method="post">
                                <div class="modal-body">
                                    <textarea class="form-control" name="denied_note" rows="3"></textarea>
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ csrf_token() }}" />
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{\App\CPU\translate('Close')}}
                                    </button>
                                    <button type="submit" class="btn btn-primary">{{\App\CPU\translate('Save
                                        changes')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @elseif($product['request_status'] == 2)
        <!-- Card -->
        <div class="card mb-3 mb-lg-5 mt-2 mt-lg-3 bg-warning">
            <!-- Body -->
            <div class="card-body text-center">
                <span class="text-dark">{{ $product['denied_note'] }}</span>
            </div>
        </div>
        @endif
        <!-- Nav -->
        <ul class="nav nav-tabs page-header-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="javascript:">
                    {{\App\CPU\translate('Room_details_&_Reviews')}}
                </a>
            </li>
        </ul>
        <!-- End Nav -->
    </div>
    <!-- End Page Header -->
    <div class="card">
        <div class="card-header">
            <div class="flex-start">
                <h5>{{ \App\CPU\translate('Room')}} {{ \App\CPU\translate('Details')}}</h5>
            </div>
            <div class="add-new flex-end">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> {{ \App\CPU\translate('Rooms')}}</button>
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ \App\CPU\translate('Tambah_kamar')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.product.add-room') }}" method="post">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $product->room_id }}">
                            <div class="form-group">
                                <label for="name">Nama / Nomor kamar</label>
                                <input class="form-control" type="text" name="name">
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="isi" type="checkbox" value="1" id="isi">
                                <label class="form-check-label" for="isi">Sudah Berpenghuni</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body" style="padding: 0">
            <div class="table-responsive">
                <table id="datatable" style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};"
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                    style="width: 100%">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">{{\App\CPU\translate('No')}}</th>
                            <th class="text-center">{{\App\CPU\translate('Name_Room')}}</th>
                            <th style="width: 5px" class="text-center">{{\App\CPU\translate('Occupant')}}</th>
                            <th class="text-center">{{\App\CPU\translate('Available')}}
                                {{\App\CPU\translate('status')}}</th>
                            <th style="width: 5px" class="text-center">{{\App\CPU\translate('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($no = 1)

                        @foreach($rooms as $k=>$p)
                        <tr>
                            {{-- <td class="text-center" scope="row">{{$rooms->firstitem()+ $k}}</td> --}}
                            <td class="text-center" scope="row">{{$no++}}</td>
                            <td class="text-center">{{ $p->name }}</td>
                            <td class="text-center">@if ($p->customer)
                                {{ $p->customer->f_name }} {{ $p->customer->l_name }}
                                @elseif ($p->user_id == 'booked')
                                    <span class="badge badge-info">Pilih ditempat</span>
                                @else
                                    <span class="badge badge-warning badge-sm">Kosong</span>
                            @endif</td>
                            <td class="text-center">
                                <label class="switch">
                                    <input type="checkbox" class="status" id="{{$p['id']}}" {{$p->available ==
                                    1?'checked':''}}>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-danger btn-sm" href="javascript:"
                                    onclick="form_alert('product-{{$p['id']}}','{{\App\CPU\translate("Ingin menghapus kamar ini?")}} ?')">
                                    <i class="tio-add-to-trash"></i> {{\App\CPU\translate('Delete')}}
                                </a>
                                <form action="{{route('admin.product.del-room',[$p['id']])}}" method="post"
                                    id="product-{{$p['id']}}">
                                    @csrf
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if(count($rooms)==0)
                    <div class="text-center p-4">
                        <img class="mb-3" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                        <p class="mb-0">{{\App\CPU\translate('No_rooms_yet')}}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if ($tempat != '[]')
    <div class="card mt-3">
        <div class="card-header">
            <div class="flex-start">
                <h5>{{ \App\CPU\translate('Pending')}} {{ \App\CPU\translate('Occupant')}}</h5>
            </div>
        </div>
        <div class="card-body" style="padding: 0">
            <div class="table-responsive">
                <table id="datatable" style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};"
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                    style="width: 100%">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">{{\App\CPU\translate('No')}}</th>
                            <th class="text-center">{{\App\CPU\translate('Name')}}</th>
                            <th style="width: 5px" class="text-center">{{\App\CPU\translate('Room_name')}}</th>
                            {{-- <th class="text-center">{{\App\CPU\translate('Berakhir')}}</th> --}}
                            {{-- <th style="width: 5px" class="text-center">{{\App\CPU\translate('Action')}}</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @php($no = 1)

                        @foreach($tempat as $p)
                        @php($users = json_decode($p['data_penyewa']))
                        <tr>
                            {{-- @php($room = App\CPU\Helpers::getRoom($o['order']['roomDetail_id'])) --}}
                            {{-- <td class="text-center" scope="row">{{$rooms->firstitem()+ $k}}</td> --}}
                            <td class="text-center" scope="row">{{$no++}}</td>
                            <td class="text-center">{{ $users->f_name }} {{ $users->l_name }}</td>
                            <td class="text-center"><a href="javascript:" class="badge badge-primary" data-toggle="modal" data-target="#selecRoom{{ $p['id'] }}">Pilih kamar</a>

                                <div class="modal fade" id="selecRoom{{ $p['id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Pilih kamar untuk {{ $users->f_name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.product.change-room') }}" method="post">
                                        <div class="modal-body">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $p['order']['id'] }}">
                                            <input type="hidden" name="user_id" value="{{ $users->id }}">
                                            <div class="form-group">
                                                <select name="room_id" class="form-control">
                                                    <option value="">-- Pilih kamar --</option>
                                                    @foreach ($rooms as $r)
                                                        @if($r['available'] == 1 || $r['user_id'] == 'booked')
                                                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                        </form>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                            <td class="text-center">
                                {{-- <a class="btn btn-danger btn-sm" href="javascript:"
                                    onclick="form_alert('product-{{$p['id']}}','{{\App\CPU\translate("Ingin menghapus kamar ini?")}} ?')">
                                    <i class="tio-add-to-trash"></i> {{\App\CPU\translate('Delete')}}
                                </a>
                                <form action="{{route('admin.product.del-room',[$p['id']])}}" method="post"
                                    id="product-{{$p['id']}}">
                                    @csrf
                                </form> --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <div class="card mt-3">
        <div class="card-header">
            <div class="flex-start">
                <h5>{{ \App\CPU\translate('Occupant')}} {{ \App\CPU\translate('Details')}}</h5>
            </div>
        </div>
        <div class="card-body" style="padding: 0">
            <div class="table-responsive">
                <table id="datatable" style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};"
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                    style="width: 100%">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">{{\App\CPU\translate('No')}}</th>
                            <th class="text-center">{{\App\CPU\translate('Name')}}</th>
                            <th style="width: 5px" class="text-center">{{\App\CPU\translate('Room_name')}}</th>
                            {{-- <th class="text-center">{{\App\CPU\translate('Berakhir')}}</th> --}}
                            {{-- <th style="width: 5px" class="text-center">{{\App\CPU\translate('Action')}}</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @php($no = 1)

                        @foreach($order as $o)
                        @php($user = json_decode($o['data_penyewa']))
                        @if ($o['order']['roomDetail_id'] !== 'ditempat')
                        @php($room = App\CPU\Helpers::getRoom($o['order']['roomDetail_id']))
                        <tr>
                            <td class="text-center" scope="row">{{$no++}}</td>
                            <td class="text-center">{{ $user->f_name }} {{ $user->l_name }}</td>
                            <td class="text-center">@if (isset($room['name']))
                                {{ $room['name'] }}
                                @else
                                <span class="badge badge-danger">Invalid room data</span>
                            @endif
                            <td class="text-center">
                                {{-- <a class="btn btn-danger btn-sm" href="javascript:"
                                    onclick="form_alert('product-{{$p['id']}}','{{\App\CPU\translate("Ingin menghapus kamar ini?")}} ?')">
                                    <i class="tio-add-to-trash"></i> {{\App\CPU\translate('Delete')}}
                                </a>
                                <form action="{{route('admin.product.del-room',[$p['id']])}}" method="post"
                                    id="product-{{$p['id']}}">
                                    @csrf
                                </form> --}}
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
                @if(count($rooms)==0)
                    <div class="text-center p-4">
                        <img class="mb-3" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                        <p class="mb-0">{{\App\CPU\translate('No_rooms_yet')}}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Card -->
    <div class="card my-3 mb-lg-5">
        <!-- Body -->
        <div class="card-body">
            <div class="row align-items-md-center gx-md-5">
                <div class="col-md-auto mb-3 mb-md-0">
                    <div class="d-flex align-items-center">
                        <img class="avatar avatar-xxl avatar-4by3 {{Session::get('direction') === " rtl" ? 'ml-4'
                            : 'mr-4' }}"
                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                            src="{{asset('storage/product')}}/{{json_decode($product['images'])[0]}}"
                            alt="Image Description">

                        <div class="d-block">
                            <h4 class="display-2 text-dark mb-0">
                                {{count($product->rating)>0?number_format($product->rating[0]->average, 2, '.', ' '):0}}
                            </h4>
                            <p> of {{$product->reviews->count()}} reviews
                                <span class="badge badge-soft-dark badge-pill {{Session::get('direction') === " rtl"
                                    ? 'mr-1' : 'ml-1' }}"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md">
                    <ul class="list-unstyled list-unstyled-py-2 mb-0">

                        @php($total=$product->reviews->count())
                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                            @php($five=\App\CPU\Helpers::rating_count($product['id'],5))
                            <span class="{{Session::get('direction') === " rtl" ? 'ml-3' : 'mr-3'
                                }}">{{\App\CPU\translate('5 star')}}</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{$total==0?0:($five/$total)*100}}%;"
                                    aria-valuenow="{{$total==0?0:($five/$total)*100}}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <span class="{{Session::get('direction') === " rtl" ? 'mr-3' : 'ml-3' }}">{{$five}}</span>
                        </li>
                        <!-- End Review Ratings -->

                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                            @php($four=\App\CPU\Helpers::rating_count($product['id'],4))
                            <span class="{{Session::get('direction') === " rtl" ? 'ml-3' : 'mr-3'
                                }}">{{\App\CPU\translate('4 star')}}</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{$total==0?0:($four/$total)*100}}%;"
                                    aria-valuenow="{{$total==0?0:($four/$total)*100}}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <span class="{{Session::get('direction') === " rtl" ? 'mr-3' : 'ml-3' }}">{{$four}}</span>
                        </li>
                        <!-- End Review Ratings -->

                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                            @php($three=\App\CPU\Helpers::rating_count($product['id'],3))
                            <span class="{{Session::get('direction') === " rtl" ? 'ml-3' : 'mr-3'
                                }}">{{\App\CPU\translate('3 star')}}</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{$total==0?0:($three/$total)*100}}%;"
                                    aria-valuenow="{{$total==0?0:($three/$total)*100}}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <span class="{{Session::get('direction') === " rtl" ? 'mr-3' : 'ml-3' }}">{{$three}}</span>
                        </li>
                        <!-- End Review Ratings -->

                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                            @php($two=\App\CPU\Helpers::rating_count($product['id'],2))
                            <span class="{{Session::get('direction') === " rtl" ? 'ml-3' : 'mr-3'
                                }}">{{\App\CPU\translate('2 star')}}</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{$total==0?0:($two/$total)*100}}%;"
                                    aria-valuenow="{{$total==0?0:($two/$total)*100}}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <span class="{{Session::get('direction') === " rtl" ? 'mr-3' : 'ml-3' }}">{{$two}}</span>
                        </li>
                        <!-- End Review Ratings -->

                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                            @php($one=\App\CPU\Helpers::rating_count($product['id'],1))
                            <span class="{{Session::get('direction') === " rtl" ? 'ml-3' : 'mr-3'
                                }}">{{\App\CPU\translate('1 star')}}</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{$total==0?0:($one/$total)*100}}%;"
                                    aria-valuenow="{{$total==0?0:($one/$total)*100}}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <span class="{{Session::get('direction') === " rtl" ? 'mr-3' : 'ml-3' }}">{{$one}}</span>
                        </li>
                        <!-- End Review Ratings -->
                    </ul>
                </div>

                <div class="col-12">
                    <hr>
                </div>
                <div class="col-4 pt-2">
                    <div class="flex-start">
                        <h4 class="border-bottom">{{$product['name']}}</h4>
                    </div>
                    <div class="flex-start">
                        <span>{{\App\CPU\translate('Price')}} : </span>
                        <span
                            class="mx-1">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product['unit_price']))}}</span>
                    </div>
                    <div class="flex-start">
                        <span>{{\App\CPU\translate('TAX')}} : </span>
                        <span class="mx-1">{{($product['tax'])}} % </span>
                    </div>
                    <div class="flex-start">
                        <span>{{\App\CPU\translate('Discount')}} : </span>
                        <span class="mx-1">{{
                            $product->discount_type=='flat'?(\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product['discount']))):
                            $product->discount.''.'%'}} </span>
                    </div>
                    <div class="flex-start">
                        <span>{{\App\CPU\translate('Current Stock')}} : </span>
                        <span class="mx-1">{{ $product->current_stock }}</span>
                    </div>
                </div>

                <div class="col-8 pt-2 border-left">

                    {{\App\CPU\translate('Rooms_Image')}}

                    <div class="row">
                        @foreach (json_decode($product->images) as $key => $photo)
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <img style="width: 100%"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset("storage/product/$photo")}}" alt="Product image">

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    </span>
                </div>
            </div>
        </div>
        <!-- End Body -->
    </div>
    <!-- End Card -->

    <!-- Card -->
    <div class="card">
        <!-- Table -->
        <div class="table-responsive datatable-custom">
            <table class="table table-borderless table-thead-bordered table-nowrap card-table"
                style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};">
                <thead class="thead-light">
                    <tr>
                        <th>{{\App\CPU\translate('Reviewer')}}</th>
                        <th>{{\App\CPU\translate('Review')}}</th>
                        <th>{{\App\CPU\translate('Date')}}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($reviews as $review)
                    <tr>
                        <td>
                            <a class="d-flex align-items-center"
                                href="{{route('admin.customer.view',[$review['customer_id']])}}">
                                <div class="avatar avatar-circle">
                                    <img class="avatar-img"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/profile/'.$review->customer->image)}}"
                                        alt="Image Description">
                                </div>
                                <div class="{{Session::get('direction') === " rtl" ? 'mr-3' : 'ml-3' }}">
                                    <span class="d-block h5 text-hover-primary mb-0">{{$review->customer['f_name']."
                                        ".$review->customer['l_name']}} <i class="tio-verified text-primary"
                                            data-toggle="tooltip" data-placement="top"
                                            title="Verified Customer"></i></span>
                                    <span class="d-block font-size-sm text-body">{{$review->customer->email}}</span>
                                </div>
                            </a>
                        </td>
                        <td>
                            <div class="text-wrap" style="width: 18rem;">
                                <div class="d-flex mb-2">
                                    <label class="badge badge-soft-info">
                                        {{$review->rating}} <i class="tio-star"></i>
                                    </label>
                                </div>

                                <p>
                                    {{$review['comment']}}
                                </p>
                            </div>
                        </td>
                        <td>
                            {{date('d M Y H:i:s',strtotime($review['created_at']))}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- End Table -->

        <!-- Footer -->
        <div class="card-footer">
            {!! $reviews->links() !!}
        </div>
        <!-- End Footer -->
    </div>
    <!-- End Card -->
</div>
@endsection

@push('script_2')
<script src="{{asset('public/assets/back-end')}}/js/tags-input.min.js"></script>
<script src="{{ asset('public/assets/select2/js/select2.min.js')}}"></script>
<script>
    $(document).on('change', '.status', function () {
        var id = $(this).attr("id");
        if ($(this).prop("checked") == true) {
            var status = 1;
        } else if ($(this).prop("checked") == false) {
            var status = 0;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('admin.product.room-update')}}",
            method: 'POST',
            data: {
                id: id,
                status: status
            },
            success: function (data) {
                if(data.success == true) {
                    toastr.success('{{\App\CPU\translate('Status_kamar_berhasil_diubah')}}');
                }
                else if(data.success == false) {
                    toastr.error('{{\App\CPU\translate('Status updated failed. Product must be approved')}}');
                    location.reload();
                }
            }
        });
    });

    $('input[name="colors_active"]').on('change', function () {
            if (!$('input[name="colors_active"]').is(':checked')) {
                $('#colors-selector').prop('disabled', true);
            } else {
                $('#colors-selector').prop('disabled', false);
            }
        });
        $(document).ready(function () {
            $('.color-var-select').select2({
                templateResult: colorCodeSelect,
                templateSelection: colorCodeSelect,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            function colorCodeSelect(state) {
                var colorCode = $(state.element).val();
                if (!colorCode) return state.text;
                return "<span class='color-preview' style='background-color:" + colorCode + ";'></span>" + state.text;
            }
        });
</script>
@endpush
