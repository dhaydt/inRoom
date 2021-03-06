@extends('layouts.back-end.app-seller')
@section('title', \App\CPU\translate('Penyewa'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <!-- Page Heading -->
    <div class="content container-fluid">
        <div class="row align-items-center mb-3">
            <div class="col-sm">
                <h1 class="page-header-title">{{\App\CPU\translate('Penyewa')}} <span
                        class="badge badge-soft-dark ml-2">{{$orders->total()}}</span>
                </h1>
            </div>
        </div>

        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="d-none d-md-flex">{{\App\CPU\translate('Daftar_penyewa')}} </h5>
                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6 mb-3 mb-lg-0">
                                <form action="{{ url()->current() }}" method="GET">
                                    <!-- Search -->
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{\App\CPU\translate('search')}}" aria-label="Search orders" value="{{ $search }}" required>
                                        <button type="submit" class="btn btn-primary">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                    <!-- End Search -->
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table id="datatable" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                style="width: 100%">
                                <thead class="thead-light">
                                <tr>
                                    <th class="text-center">{{\App\CPU\translate('Booking_ID')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Star_date')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Property')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Duration')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Payment_type')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Occupant')}} {{\App\CPU\translate('Status')}} </th>
                                    <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $k=>$order)
                                @php($detail = json_decode($order->details[0]->product_details))
                                @php($user = $order->customer)
                                @php($district = strtolower($detail->kost->district))
                                @php($city = strtolower($detail->kost->city))
                                    <tr>
                                        <td>
                                            @if ($order->customer)
                                                <a href="{{route('seller.booked.detail',$order['id'])}}">{{$order['id']}}</a>
                                            @else
                                                <span>{{ $order['id'] }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{date('d M Y',strtotime($order['mulai']))}}</td>
                                        <td>
                                            @if($order->customer)
                                                <span class="text-body text-capitalize">{{ $detail->kost->name }} {{ $detail->type }} {{ $district }} {{ $city }}</span>
                                            @else
                                                <label class="badge badge-danger">{{\App\CPU\translate('invalid_property_data')}}</label>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ $order->durasi }} {{ App\CPU\Translate('month') }}
                                        </td>
                                        <td class="text-center">
                                            @if (count($order->booked) > 1)
                                                <span class="badge badge-danger">Per Month</span>
                                            @else
                                                <span class="badge badge-success">Paid All</span>
                                            @endif
                                        </td>
                                        {{-- <td> {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->order_amount))}}</td> --}}
                                        <td class="text-capitalize text-center">
                                            @if (isset($user))
                                                {{ $user->f_name }} {{ $user->l_name }}
                                            @else
                                                <span class="badge badge-danger">Invalid customer data</span>
                                            @endif
                                        </td>
                                            <td>
                                                @if ($order->customer)
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-secondary dropdown-toggle"
                                                            type="button"
                                                            id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">
                                                        <i class="tio-settings"></i>
                                                    </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item"
                                                            href="{{route('seller.orders.details',[$order['id']])}}"><i
                                                                class="tio-visible"></i> {{\App\CPU\translate('view')}}</a>
                                                        <a class="dropdown-item" target="_blank"
                                                            href="{{route('seller.orders.generate-invoice',[$order['id']])}}"><i
                                                                class="tio-download"></i> {{\App\CPU\translate('invoice')}}</a>
                                                    </div>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="card-footer">
                        {{$orders->links()}}
                    </div>
                    @if(count($orders)==0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                        </div>
                    @endif
                    <!-- End Footer -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
@endpush
