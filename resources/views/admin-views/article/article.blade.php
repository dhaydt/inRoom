@extends('layouts.back-end.app')

@push('css_or_js')
<link href="{{asset('public/assets/back-end/css/tags-input.min.css')}}" rel="stylesheet">
<link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a
                    href="{{route('admin.dashboard.index')}}">{{\App\CPU\translate('Dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('Article')}}
            </li>
        </ol>
    </nav>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <form class="product-form" action="{{ route('admin.article.updateArticle') }}" method="post"
                style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};" id="product_form">
                @csrf
                <div class="card">
                    <div class="card-header">
                        @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
                        @php($language = $language->value ?? null)
                        @php($default_lang = 'en')

                        @php($default_lang = json_decode($language)[0])
                        <h4>{{\App\CPU\translate('Article_footer')}}</h4>
                    </div>

                    <div class="card-body">
                        <div class="lang_form" id="form">
                            <input type="hidden" name="lang[]" value="en">
                            <div class="form-group">
                                <textarea name="description" class="editor textarea" cols="30" rows="10"
                                    style="display: none" required>{!! $data->value !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-footer">
                    <div class="row">
                        <div class="col-md-12" style="padding-top: 20px">
                            <button type="button" onclick="check()"
                                class="btn btn-primary">{{\App\CPU\translate('Submit')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12 mt-4">
            <form class="product-form" action="{{ route('admin.article.updatePanduan') }}" method="post"
                style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};" id="panduan_form">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('How_To_Use')}}</h4>
                    </div>

                    <div class="card-body">
                        <div class="lang_form" id="form">
                            <input type="hidden" name="lang[]" value="en">
                            <div class="form-group">
                                <textarea name="panduan" class="editor textarea" cols="30" rows="10"
                                    style="display: none" required>{!! $panduan->value !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-footer">
                    <div class="row">
                        <div class="col-md-12" style="padding-top: 20px">
                            <button type="button" onclick="savePanduan()"
                                class="btn btn-primary">{{\App\CPU\translate('Save_how_to_use')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{asset('public/assets/back-end')}}/js/tags-input.min.js"></script>
<script src="{{ asset('public/assets/select2/js/select2.min.js')}}"></script>
<script src="{{asset('public/assets/back-end/js/spartan-multi-image-picker.js')}}"></script>
<script>
    function check(){
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure')}}?',
                text: '',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#377dff',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                for ( instance in CKEDITOR.instances ) {
                    CKEDITOR.instances[instance].updateElement();
                }
                var formData = new FormData(document.getElementById('product_form'));
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{route('admin.article.updateArticle')}}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    error: function(err){console.log(err.responseJSON.errors)},
                    success: function (data) {
                        if (data.errors) {
                            for (var i = 0; i < data.errors.length; i++) {
                                toastr.error(data.errors[i].message, {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        } else {
                            toastr.success('{{\App\CPU\translate('Article footer updated successfully!')}}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            $('#product_form').submit();
                        }
                    }
                });
            })
        };

    function savePanduan(){
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure')}}?',
                text: '',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#377dff',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                for ( instance in CKEDITOR.instances ) {
                    CKEDITOR.instances[instance].updateElement();
                }
                var formData = new FormData(document.getElementById('panduan_form'));
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{route('admin.article.updatePanduan')}}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    error: function(err){console.log(err.responseJSON.errors)},
                    success: function (data) {
                        if (data.errors) {
                            for (var i = 0; i < data.errors.length; i++) {
                                toastr.error(data.errors[i].message, {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        } else {
                            toastr.success('{{\App\CPU\translate('How to use updated successfully!')}}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            $('#panduan_form').submit();
                        }
                    }
                });
            })
        };
</script>

{{--ck editor--}}
<script src="{{asset('/')}}vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script src="{{asset('/')}}vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
<script>
    $('.textarea').ckeditor({
            contentsLangDirection : '{{Session::get('direction')}}',
        });
</script>
{{--ck editor--}}
@endpush
