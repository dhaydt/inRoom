@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Applied_job'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header mb-1">
            <div class="flex-between align-items-center">
                <div>
                    <h1 class="page-header-title">{{\App\CPU\translate('Applied_job')}} <span
                            class="badge badge-soft-dark mx-2">{{$orders->total()}}</span></h1>
                </div>
                <div>
                    <i class="tio-shopping-cart" style="font-size: 30px"></i>
                </div>
            </div>
            <!-- End Row -->

            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <span class="hs-nav-scroller-arrow-prev" style="display: none;">
              <a class="hs-nav-scroller-arrow-link" href="javascript:;">
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
                        <a class="nav-link active" href="#">{{\App\CPU\translate('Applied_list')}}</a>
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
                                       placeholder="{{\App\CPU\translate('Search orders')}}" aria-label="Search orders" value="{{ $search }}"
                                       required>
                                <button type="submit" class="btn btn-primary">{{\App\CPU\translate('search')}}</button>
                            </div>
                            <!-- End Search -->
                        </form>
                    </div>
                    <div>
                        <label> {{\App\CPU\translate('inhouse_booking_only')}} : </label>
                        <label class="switch ml-3">
                            <input type="checkbox" class="status"
                                   onclick="filter_order()" {{session()->has('show_inhouse_bookings') && session('show_inhouse_bookings')==1?'checked':''}}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                       style="width: 100%; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                    <thead class="thead-light">
                    <tr>
                        <th class="text-center">
                            {{\App\CPU\translate('SL')}}#
                        </th>
                        <th class="text-center">{{\App\CPU\translate('ID')}}</th>
                        <th class="text-center">{{\App\CPU\translate('Name')}}</th>
                        <th class="text-center">{{\App\CPU\translate('Position')}}</th>
                        <th class="text-center">{{\App\CPU\translate('Company_name')}}</th>
                        <th class="text-center">{{\App\CPU\translate('Sallary')}}</th>
                        <th class="text-center">{{\App\CPU\translate('Applied')}} {{\App\CPU\translate('Status')}} </th>
                        <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($orders as $key=>$order)
                    @php($detail = $order->job))
                    @php($district = strtolower($detail->district))
                    @php($city = strtolower($detail->city))
                        <tr class="status-{{$order['order_status']}} class-all">
                            <td class="text-center">
                                {{$orders->firstItem()+$key}}
                            </td>
                            <td class="table-column-pl-0 text-center">
                                <a href="{{route('admin.jobs.details',['order_id'=>$order['id']])}}">{{$order['id']}}</a>
                            </td>
                            <td class="text-center">{{ $order->name }}</td>
                            <td class="text-center">{{ $order->job->name }}</td>
                            <td>
                               {{ $order->job->company_name }}
                            </td>
                            <td> {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->job->gaji))}}</td>
                            <td class="text-capitalize text-center">
                                @if($order['job_status']=='applied')
                                    <span class="badge badge-soft-warning ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-warning"
                                              style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{\App\CPU\translate('Lamaran_masuk')}}
                                      </span>

                                @elseif($order['job_status']=='viewed' || $order['order_status']=='out_for_delivery')
                                    <span class="badge badge-soft-warning ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-warning"
                                              style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{\App\CPU\translate('dilihat')}}
                                      </span>
                                @elseif($order['job_status']=='terima')
                                    <span class="badge badge-soft-success ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-success"
                                              style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{\App\CPU\translate('diterima')}}
                                      </span>
                                @elseif($order['job_status']=='tolak')
                                    <span class="badge badge-danger ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-danger"
                                              style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{\App\CPU\translate('ditolak')}}
                                      </span>
                                @elseif($order['job_status']=='delivered')
                                    <span class="badge badge-soft-success ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-success"
                                              style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{\App\CPU\translate($order['order_status'])}}
                                      </span>
                                @else
                                    <span class="badge badge-soft-danger ml-2 ml-sm-3">
                                        <span class="legend-indicator bg-danger"
                                              style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{\App\CPU\translate($order['order_status'])}}
                                      </span>
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
