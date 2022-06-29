@extends('layouts.back-end.app')

@section('title',\App\CPU\translate('Property List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard.index')}}">{{\App\CPU\translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('Jobs_Vacancy')}}</li>

            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{-- <div class="flex-start">
                            <h5>{{ \App\CPU\translate('Property')}} {{ \App\CPU\translate('Table')}}</h5>
                            <h5 class="mx-1"><span style="color: red;">({{ $kost->total() }})</span></h5>
                        </div> --}}

                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="col-lg-2"></div>
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
                                            placeholder="{{\App\CPU\translate('Search_by_Job_Name')}}" aria-label="Search orders" value="{{ $search }}" required>
                                        <button type="submit" class="btn btn-primary">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                    <!-- End Search -->
                                </form>
                            </div>
                            <div class="col-lg-4">
                                <a href="{{route('admin.jobs.add-new')}}" class="btn btn-primary float-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">
                                    <i class="tio-add-circle"></i>
                                    <span class="text">{{\App\CPU\translate('New_job')}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table id="datatable"
                                   style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                   class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                   style="width: 100%">
                                <thead class="thead-light">
                                <tr>
                                    <th class="text-center">{{\App\CPU\translate('SL#')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Name')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Posted_by')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Status_employe')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Company')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Sallary')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Expired_in')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('status')}}</th>
                                    <th style="width: 5px" class="text-center">{{\App\CPU\translate('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $k=>$p)
                                    <tr>
                                        <th class="text-center align-middle" scope="row">{{$products->firstitem()+ $k}}</th>
                                        <td class="text-center capitalize">
                                                {{$p['name']}}
                                        </td>
                                        @if ($p->seller && $p->added_by == 'seller')
                                        <td class="text-center capitalize">
                                                {{$p->seller->f_name.' '.$p->seller->l_name}}
                                        </td>
                                        @elseif($p->added_by == 'admin')
                                        <td class="text-center capitalize">
                                            <span class="badge badge-success">Inroom Jobs</span>
                                        </td>
                                        @else
                                        <td class="text-center capitalize">
                                            <span class="badge badge-danger">Invalid owner data</span>
                                        </td>
                                        @endif
                                        <td class="text-center capitalize">
                                            {{ $p['status_employe']  }}
                                        </td>
                                        <td class="text-center capitalize">
                                            {{ $p['company_name']  }}
                                        </td>
                                        <td class="text-center capitalize">
                                            {{ \App\CPU\Helpers::currency_converter($p['gaji'])  }}
                                        </td>
                                        <td class="text-center capitalize">
                                            @if (isset($p['expire']))
                                            {{ Carbon\Carbon::parse($p['expire'])->format('d M, Y')  }}
                                            @endif
                                        </td>
                                        <td class="text-center capitalize">
                                            <label class="switch">
                                                <input type="checkbox" class="status"
                                                       id="{{$p['id']}}" {{$p->status == 1?'checked':''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-primary btn-sm"
                                               href="{{route('admin.jobs.edit',[$p['id']])}}">
                                                <i class="tio-edit"></i>{{\App\CPU\translate('Edit')}}
                                            </a>
                                            <a class="btn btn-danger btn-sm" href="javascript:"
                                               onclick="form_alert('product-{{$p['id']}}','{{\App\CPU\translate("Want to delete this item")}} ?')">
                                               <i class="tio-add-to-trash"></i> {{\App\CPU\translate('Delete')}}
                                            </a>
                                            <form action="{{route('admin.jobs.delete',[$p['id']])}}"
                                                  method="post" id="product-{{$p['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Footer -->
                     <div class="card-footer">
                        {{$products->links()}}
                    </div>
                    @if(count($products)==0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{\App\CPU\translate('no_properties_yet')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });

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
                url: "{{route('admin.jobs.status-update')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function (data) {
                    if(data.success == true) {
                        toastr.success('{{\App\CPU\translate('Status updated successfully')}}');
                    }
                    else if(data.success == false) {
                        toastr.error('{{\App\CPU\translate('Status updated failed. Product must be approved')}}');
                        location.reload();
                    }
                }
            });
        });
    </script>
@endpush
