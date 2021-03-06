@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Penyewa'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-1">
        <div class="flex-between align-items-center">
            <div>
                <h1 class="page-header-title">{{\App\CPU\translate('Penyewa')}} <span
                        class="badge badge-soft-dark mx-2">{{$orders->total()}}</span></h1>
            </div>
            <div>
                <i class="tio-shopping-cart" style="font-size: 30px"></i>
            </div>
        </div>
        <!-- End Row -->
        <!-- Nav Scroller -->
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <span class="hs-nav-scroller-arrow-prev" style="display: none">
                <a class="hs-nav-scroller-arrow-link" href="javascript:">
                    <i class="tio-chevron-left"></i>
                </a>
            </span>

            <span class="hs-nav-scroller-arrow-next" style="display: none;">
                <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                    <i class="tio-chevron-right"></i>
                </a>
            </span>

            <!-- Nav -->
            <ul class="nav nav-tabs page-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#">{{\App\CPU\translate('Penyewa_kamar')}}</a>
                </li>
            </ul>
            <!-- End Nav -->
        </div>
        <!-- End Nav Scroller -->
    </div>
    <!-- End Page Header -->

    <!-- Card -->
    <div class="card">
        <!-- Header -->
        <div class="card-header">
            <div class="flex-between justify-content-between align-items-center flex-grow-1">
                <div>
                    <form action="{{ url()->current() }}" method="GET">
                        <!-- Search -->
                        <div class="input-group input-group-merge input-group-flush">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                placeholder="{{\App\CPU\translate('Cari_penyewa')}}" aria-label="Search orders"
                                value="{{ $search }}" required>
                            <button type="submit" class="btn btn-primary">{{\App\CPU\translate('search')}}</button>
                        </div>
                        <!-- End Search -->
                    </form>
                </div>
                <div>
                    <label> {{\App\CPU\translate('inhouse_booking_only')}} : </label>
                    <label class="switch ml-3">
                        <input type="checkbox" class="status" onclick="filter_order()"
                            {{session()->has('show_inhouse_orders') &&
                        session('show_inhouse_orders')==1?'checked':''}}>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            <!-- End Row -->
        </div>
        <!-- End Header -->

        <!-- Table -->
        <div class="table-responsive datatable-custom">
            <table
                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                style="width: 100%; text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }}">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">{{\App\CPU\translate('Booking_ID')}}</th>
                        <th class="text-center">{{\App\CPU\translate('Star_date')}}</th>
                        <th class="text-center">{{\App\CPU\translate('Property')}}</th>
                        <th class="text-center">{{\App\CPU\translate('Duration')}}</th>
                        <th class="text-center">{{\App\CPU\translate('Payment_type')}}</th>
                        <th class="text-center">{{\App\CPU\translate('Occupant')}}</th>
                        <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $key=>$order)
                    @php($detail = json_decode($order->details[0]->product_details))
                    @php($user = $order->customer)
                    @php($district = strtolower($detail->kost->district))
                    @php($city = strtolower($detail->kost->city))
                    {{-- {{ dd(($user)) }} --}}
                    <tr class="status-{{$order['order_status']}} class-all">
                        <td class="table-column-pl-0 text-center">
                            @if($order->customer)
                            <a href="{{route('admin.booked.detail',['order'=>$order['id']])}}">{{$order['id']}}</a>
                            @else
                            <span>{{$order['id']}}</span>
                            @endif
                        </td>
                        <td class="text-center">{{date('d M Y',strtotime($order['mulai']))}}</td>
                        <td>
                            @if($order->customer)
                            <span class="text-body text-capitalize">{{ $detail->kost->name }},
                                {{ $detail->type }}, {{ $district }}, {{ $city }}</span>
                            @else
                            <label class="badge badge-danger">{{\App\CPU\translate('invalid_customer_data')}}</label>
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
                        <td class="text-capitalize text-center">
                            @if (isset($user))
                                {{ $user->f_name }} {{ $user->l_name }}
                            @else
                                <span class="badge badge-danger">Invalid customer data</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="tio-settings"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item"
                                        href="{{route('admin.orders.details',['id'=>$order['id']])}}"><i
                                            class="tio-visible"></i> {{\App\CPU\translate('view')}}</a>
                                    <a class="dropdown-item" target="_blank"
                                        href="{{route('admin.orders.generate-invoice',[$order['id']])}}"><i
                                            class="tio-download"></i> {{\App\CPU\translate('invoice')}}</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- End Table -->

        <!-- Footer -->
        <div class="card-footer">
            <!-- Pagination -->
            <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                <div class="col-sm-auto">
                    <div class="d-flex justify-content-center justify-content-sm-end">
                        <!-- Pagination -->
                        {!! $orders->links() !!}
                    </div>
                </div>
            </div>
            <!-- End Pagination -->
        </div>
        <!-- End Footer -->
    </div>
    <!-- End Card -->
</div>
@endsection

@push('script_2')
<script>
    function filter_order() {
            $.get({
                url: '{{route('admin.orders.inhouse-order-filter')}}',
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    toastr.success('{{\App\CPU\translate('order_filter_success')}}');
                    location.reload();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        };
</script>
@endpush
