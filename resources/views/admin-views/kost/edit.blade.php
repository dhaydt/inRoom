@extends('layouts.back-end.app')

@push('css_or_js')
<link href="{{asset('public/assets/back-end/css/tags-input.min.css')}}" rel="stylesheet">
<link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<style>
    .select2-container--default input.select2-search__field{
        padding-left: 10px !important;
    }
    .select2-container--default .select2-selection--single span.select2-selection__rendered{
        line-height: 36px;
        font-size: 14px;
        margin-left: 6px;
    }
</style>

<div class="content container-fluid">
    <!-- Page Heading -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a
                    href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page"><a
                    href="{{route('admin.property.list', ['type' => 'all'])}}">{{\App\CPU\translate('Property')}}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Edit')}}</li>
        </ol>
    </nav>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <form class="product-form" action="{{route('admin.property.update',$product->id)}}" method="post"
                enctype="multipart/form-data" id="product_form">
                @csrf
                <div class="card">
                    <div class="card-header">
                        @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
                        @php($language = $language->value ?? null)
                        @php($default_lang = 'en')

                        @php($default_lang = json_decode($language)[0])
                        @php($lang = 'en')
                    </div>

                    <div class="card-body">
                        <div class="{{$lang != 'en'? 'd-none':''}} lang_form" id="{{$lang}}-form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label label-name" for="{{$lang}}_name">{{
                                            \App\CPU\translate('Nama')}}</label>
                                        <input type="text" {{$lang=='en' ? 'required' :''}} name="name" id="{{$lang}}_name"
                                            value="{{$translate[$lang]['name']??$product['name']}}" class="form-control"
                                            placeholder="New Product" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label label-name mb-0" for="tipe">{{\App\CPU\translate('Disewakan
                                            untuk putra/ putri')}} ?</label>
                                        <small>Menerima penyewa putra/ putri/ campur</small>
                                        <select class="form-control" id="tipe" name="penghuni">
                                            <option class="" value="{{ $product->penghuni }}">{{ $product->penghuni }}</option>
                                            <option value="campur">Campur</option>
                                            <option value="putra">Putra</option>
                                            <option value="putri">Putri</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label label-name mb-0"
                                            for="cat">{{\App\CPU\translate('Jenis_properti')}}</label>
                                        <small>Pilih jenis property anda</small>
                                        <select class="form-control" id="cat" name="category">
                                            @foreach ($cat as $c)
                                            <option value="{{ $c->id }}" {{ ($c->id == $product->category_id)? 'selected' : '' }}>{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label label-name mb-0"
                                            for="cat">{{\App\CPU\translate('Dekat_dengan_perguruan_tinggi_apa')}} ?</label>
                                        <small>Jika ada, property anda dekat dengan kampus apa? (optional)</small>
                                        <select class="js-example-basic-single form-control" name="ptn" id="ptn">
                                            <option value="">-- Pilih kampus terdekat --</option>
                                            @foreach ($ptn as $p)
                                            <option value="{{ $p->id }}"{{ ($p->id == $product->ptn_id)? 'selected' : '' }}>{{ $p->name }}</option>
                                            @endforeach
                                          </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="input-label label-name mb-0"
                                    for="tipe">{{\App\CPU\translate('Deskripsi_kos')}}</label>
                                <small>Ceritakan hal menarik tentang kos Anda.</small>
                                <textarea name="description" class="textarea" style="display: none"
                                    required>{!! $product['deskripsi'] !!}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="input-label label-name mb-0"
                                    for="tipe">{{\App\CPU\translate('Catatan_lainnya')}}</label>
                                <small>Jika ada pemberitahuan yang ingin disampaikan.</small>
                                <input type="text" name="note" class="form-control" value="{{ $product['note'] }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Property -->
                <div class="card mt-2 rest-part">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('Alamat_properti')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    @php($prov = \App\CPU\Helpers::province())
                                    <label class="input-label label-name mb-0"
                                        for="cat">{{\App\CPU\translate('Provinsi')}}</label>
                                    <select class="form-control" id="prov" name="province">
                                        <option value="">-- Pilih provinsi --</option>
                                        @foreach ($prov as $key => $val)
                                        <option value="{{ $key }}" {{ ($product->province == $val)? 'selected': '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{-- {{ dd($product) }} --}}
                                    <label class="input-label label-name mb-0"
                                        for="cat">{{\App\CPU\translate('Kabupaten_/_Kota')}}</label>
                                    <select class="form-control" id="city" name="city">
                                        <option value="{{ $product->cities->id }}" selected>{{ $product->city }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label label-name mb-0"
                                        for="cat">{{\App\CPU\translate('Kecamatan')}}</label>
                                    <select class="form-control" id="district" name="district">
                                        <option value="{{ $product->dis->id }}" selected>{{ $product->district }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label label-name mb-0"
                                        for="cat">{{\App\CPU\translate('Catatan_alamat')}}</label>
                                    <small>Deskripsi patokan agar kos mudah ditemukan</small>
                                    <textarea class="form-group w-100" name="noteAddress" id="" cols="30"
                                        rows="3">{!! $product->note_address !!}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-2 rest-part">
                    <small class="px-3 pt-2" style="color: red; font-size: 90%;">* {{\App\CPU\translate('Foto landscape untuk mendapatkan tampilan terbaik.')}}</small>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name" class="label-name">{{\App\CPU\translate('Foto_bangunan_tampak_depan')}}</label>
                                </div>
                                <div style="max-width:200px;">
                                    <div class="row" id="depan"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name" class="label-name">{{\App\CPU\translate('Foto_dalam_bangunan')}}</label>
                                </div>
                                <div style="max-width:200px;">
                                    <div class="row" id="dalam"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name" class="label-name">{{\App\CPU\translate('Foto_bangunan_dari_jalan')}}</label>
                                </div>
                                <div style="max-width:200px;">
                                    <div class="row" id="jalan"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-2 rest-part">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('Aturan')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label label-name mb-0"
                                        for="tipe">{{\App\CPU\translate('Peraturan_kos')}}</label>
                                    <small class="text-info">Kos Anda tertib atau santai? Silakan tentukan aturan kos di
                                        sini.</small>
                                    <div class="row">
                                        @foreach ($rules as $r)
                                        <div class="col-6 mb-2">
                                            <div class="form-check" style="margin-left: 15px">
                                                <input class="form-check-input" name="aturan[]" type="checkbox" @foreach ($rule as $a) @if ($a==$r->id) checked
                                                    @endif @endforeach
                                                value="{{ $r->id }}" id="flexCheckDefault">
                                                <label class="form-check-label capitalize" for="flexCheckDefault">
                                                    {{ $r->name }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-2 rest-part">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('Fasilitas')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label label-name"
                                        for="tipe">{{\App\CPU\translate('Fasilitas_umum')}}</label>
                                    <div class="row">
                                        @foreach ($fas as $f)
                                        <div class="col-6 mb-2">
                                            <div class="form-check" style="margin-left: 15px">
                                                <input class="form-check-input" name="fasilitas[]" type="checkbox"
                                                @foreach ($fasilitas as $fa) @if ($fa==$f->id) checked @endif @endforeach
                                                    value="{{ $f->id }}" id="flexCheckDefault">
                                                <label class="form-check-label capitalize" for="flexCheckDefault">
                                                    {{ $f->name }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-footer">
                        <div class="row">
                            <div class="col-md-12" style="padding-top: 20px">
                                @if($product['request_status'] == 2)
                                <button type="button" onclick="check()" class="btn btn-primary">{{
                                    \App\CPU\translate('resubmit') }}</button>
                                @else
                                <button type="button" onclick="check()" class="btn btn-primary">{{
                                    \App\CPU\translate('update') }}</button>
                                @endif
                            </div>
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
    new TomSelect("#select-beast",{
        create: true,
        sortField: {
            field: "text",
            direction: "asc"
        }
    });
</script>
<script>
     $(document).ready(function(){
        $('.js-example-basic-single').select2();
        var $prov = $('select[name=province] option').filter(':selected').val()
        getProv($prov);
        var $city = $('select[name=city] option').filter(':selected').val()
        getCity($city)

    })
    $('#prov').on('change', function(){
        var $prov = $('select[name=province] option').filter(':selected').val()
        jQuery.ajax({
                    // url yg di root yang kita buat tadi
                    url:'/admin/city/'+$prov,
                    // aksion GET, karena kita mau mengambil data
                    type:'GET',
                    // type data json
                    dataType:'json',
                    // jika data berhasil di dapat maka kita mau apain nih
                    success:function(data){
                        console.log(data);
                        // jika tidak ada select dr provinsi maka select kota kososng / empty
                        // $('select[name="city"]').empty();
                        // // jika ada kita looping dengan each
                        $.each(data, function(key, value){
                            // console.log(key, value)
                            kota = value
                            id = key
                        // // perhtikan dimana kita akan menampilkan data select nya, di sini saya memberi name select kota adalah kota_id
                        $('select[name="city"]').append(`<option value="${id}">
                            ${kota}
                        </option>`);

                        $('select[name="city"]').removeAttr('disabled');
                        $('#loading').hide();
                        });
                    }
                });
        });

        function getProv($prov){
            jQuery.ajax({
                    // url yg di root yang kita buat tadi
                    url:'/admin/city/'+$prov,
                    // aksion GET, karena kita mau mengambil data
                    type:'GET',
                    // type data json
                    dataType:'json',
                    // jika data berhasil di dapat maka kita mau apain nih
                    success:function(data){
                        console.log(data);
                        // jika tidak ada select dr provinsi maka select kota kososng / empty
                        // $('select[name="city"]').empty();
                        // // jika ada kita looping dengan each
                        $.each(data, function(key, value){
                            // console.log(key, value)
                            kota = value
                            id = key
                        // // perhtikan dimana kita akan menampilkan data select nya, di sini saya memberi name select kota adalah kota_id
                        $('select[name="city"]').append(`<option value="${id}">
                            ${kota}
                        </option>`);

                        $('select[name="city"]').removeAttr('disabled');
                        $('#loading').hide();
                        });
                    }
                });
            }

        function getCity($city){
            jQuery.ajax({
                    // url yg di root yang kita buat tadi
                    url:'/admin/district/'+$city,
                    // aksion GET, karena kita mau mengambil data
                    type:'GET',
                    // type data json
                    dataType:'json',
                    // jika data berhasil di dapat maka kita mau apain nih
                    success:function(data){
                        console.log(data);
                        // jika tidak ada select dr provinsi maka select kota kososng / empty
                        $('select[name="district"]').empty();
                        // // jika ada kita looping dengan each
                        $.each(data, function(key, value){
                            // console.log(key, value)
                            kota = value
                            id = key
                        // // perhtikan dimana kita akan menampilkan data select nya, di sini saya memberi name select kota adalah kota_id
                        $('select[name="district"]').append(`<option value="${kota}">
                            ${kota}
                        </option>`);

                        $('select[name="district"]').removeAttr('disabled');
                        $('#loading').hide();
                        });
                    }
                });
        }

        $('#city').on('change', function(){
        var $city = $('select[name=city] option').filter(':selected').val()
        jQuery.ajax({
                    // url yg di root yang kita buat tadi
                    url:'/admin/district/'+$city,
                    // aksion GET, karena kita mau mengambil data
                    type:'GET',
                    // type data json
                    dataType:'json',
                    // jika data berhasil di dapat maka kita mau apain nih
                    success:function(data){
                        console.log(data);
                        // jika tidak ada select dr provinsi maka select kota kososng / empty
                        $('select[name="district"]').empty();
                        // // jika ada kita looping dengan each
                        $.each(data, function(key, value){
                            // console.log(key, value)
                            kota = value
                            id = key
                        // // perhtikan dimana kita akan menampilkan data select nya, di sini saya memberi name select kota adalah kota_id
                        $('select[name="district"]').append(`<option value="${kota}">
                            ${kota}
                        </option>`);

                        $('select[name="district"]').removeAttr('disabled');
                        $('#loading').hide();
                        });
                    }
                });
        });

    var depan = '{{asset('storage/kost').'/'.$depan ?? asset('public/assets/back-end/img/400x400/img2.jpg')}}';
    var dalam = '{{asset('storage/kost').'/'.$dalam ?? asset('public/assets/back-end/img/400x400/img2.jpg')}}';
    var jalan = '{{asset('storage/kost').'/'.$jalan ?? asset('public/assets/back-end/img/400x400/img2.jpg')}}';
        $(function () {
            $("#depan").spartanMultiImagePicker({
                fieldName: 'depan',
                maxCount: 1,
                rowHeight: 'auto',
                groupClassName: 'col-12',
                maxFileSize: '',
                placeholderImage: {
                    image: depan,
                    width: '100%',
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{\App\CPU\translate('Please only input png or jpg type file')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{\App\CPU\translate('File size too big')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });

            $("#dalam").spartanMultiImagePicker({
                fieldName: 'dalam',
                maxCount: 1,
                rowHeight: 'auto',
                groupClassName: 'col-12',
                maxFileSize: '',
                placeholderImage: {
                    image: dalam,
                    width: '100%',
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{\App\CPU\translate('Please only input png or jpg type file')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{\App\CPU\translate('File size too big')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });

            $("#jalan").spartanMultiImagePicker({
                fieldName: 'jalan',
                maxCount: 1,
                rowHeight: 'auto',
                groupClassName: 'col-12',
                maxFileSize: '',
                placeholderImage: {
                    image: jalan,
                    width: '100%',
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{\App\CPU\translate('Please only input png or jpg type file')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{\App\CPU\translate('File size too big')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });

        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload").change(function () {
            readURL(this);
        });

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
</script>

<script>
    function getRequest(route, id, type) {
            $.get({
                url: route,
                dataType: 'json',
                success: function (data) {
                    if (type == 'select') {
                        $('#' + id).empty().append(data.select_tag);
                    }
                },
            });
        }
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

<script>
    function check(){
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            var formData = new FormData(document.getElementById('product_form'));
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.property.update',$product->id)}}',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{\App\CPU\translate('kamar_berhassil_di_update!')}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        $('#product_form').submit();
                    }
                }
            });
        };
</script>

<script>
    update_qty();

        function update_qty() {
            var total_qty = 0;
            var qty_elements = $('input[name^="qty_"]');
            for (var i = 0; i < qty_elements.length; i++) {
                total_qty += parseInt(qty_elements.eq(i).val());
            }
            if (qty_elements.length > 0) {

                $('input[name="current_stock"]').attr("readonly", true);
                $('input[name="current_stock"]').val(total_qty);
            } else {
                $('input[name="current_stock"]').attr("readonly", false);
            }
        }

        $('input[name^="qty_"]').on('keyup', function () {
            var total_qty = 0;
            var qty_elements = $('input[name^="qty_"]');
            for (var i = 0; i < qty_elements.length; i++) {
                total_qty += parseInt(qty_elements.eq(i).val());
            }
            $('input[name="current_stock"]').val(total_qty);
        });
</script>

<script>
    $(".lang_link").click(function (e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#" + lang + "-form").removeClass('d-none');
            if (lang == '{{$default_lang}}') {
                $(".rest-part").removeClass('d-none');
            } else {
                $(".rest-part").addClass('d-none');
            }
        })
</script>
@endpush
