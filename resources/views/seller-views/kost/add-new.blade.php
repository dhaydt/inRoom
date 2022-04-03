@extends('layouts.back-end.app-seller')

@push('css_or_js')
<link href="{{asset('public/assets/back-end/css/tags-input.min.css')}}" rel="stylesheet">
<link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
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
@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a
                    href="{{route('seller.dashboard.index')}}">{{\App\CPU\translate('Dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page"><a
                    href="{{route('seller.property.list', ['type' => 'in_house'])}}">{{\App\CPU\translate('Property')}}</a>
            </li>
            <li class="breadcrumb-item">{{\App\CPU\translate('Add_new')}}</li>
        </ol>
    </nav>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">

            <form class="product-form" action="" method="post" enctype="multipart/form-data"
                style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};" id="product_form">
                @csrf
                <div class="card">
                    <div class="card-header">
                        @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
                        @php($language = $language->value ?? null)
                        @php($default_lang = 'en')

                        @php($default_lang = json_decode($language)[0])
                        <h4>{{\App\CPU\translate('Info_Properti')}}</h4>
                    </div>

                    <div class="card-body">
                        <div class="lang_form" id="form">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label label-name mb-0"
                                            for="name">{{\App\CPU\translate('Apa_nama_kos_ini')}} ?</label>
                                        <small>Saran: Kos (spasi) Nama Kos, Tanpa Nama Kecamatan dan Kota</small>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Contoh : Kos Fulan" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label label-name mb-0"
                                            for="tipe">{{\App\CPU\translate('Disewakan
                                            untuk putra/ putri')}} ?</label>
                                        <small>Menerima penyewa putra/ putri/ campur</small>
                                        <select class="form-control" id="tipe" name="penghuni">
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
                                        <small>Pilih jenis / kategori property anda</small>
                                        <select class="form-control" id="cat" name="category">
                                            <option value="">-- Pilih jenis properti --</option>
                                            @foreach ($cat as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
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
                                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="lang[]" value="en">
                            <div class="form-group">
                                <label class="input-label label-name mb-0"
                                    for="tipe">{{\App\CPU\translate('Deskripsi_kos')}}</label>
                                <small>Ceritakan hal menarik tentang kos Anda.</small>
                                <textarea name="description" class="editor textarea" cols="30" rows="10"
                                    style="display: none" required>{{old('details')}}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="input-label label-name mb-0"
                                    for="tipe">{{\App\CPU\translate('Catatan_lainnya')}}</label>
                                <small>Jika ada pemberitahuan yang ingin disampaikan.</small>
                                <input type="text" name="note" class="form-control">
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
                                        <option value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label label-name mb-0"
                                        for="cat">{{\App\CPU\translate('Kabupaten_/_Kota')}}</label>
                                    <select class="form-control" id="city" name="city">
                                        <option value="">-- Pilih kota --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label label-name mb-0"
                                        for="cat">{{\App\CPU\translate('Kecamatan')}}</label>
                                    <select class="form-control" id="district" name="district">
                                        <option value="">-- Pilih kecamatan --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label label-name mb-0"
                                        for="cat">{{\App\CPU\translate('Catatan_alamat')}}</label>
                                    <small>Deskripsi patokan agar kos mudah ditemukan</small>
                                    <textarea class="form-group w-100" name="noteAddress" id="" cols="30"
                                        rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Property-->
                <div class="card mt-2 rest-part">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('Pasang_foto_terbaik_kos_Anda')}} <small class="px-1 pt-2" style="color: red; font-size: 90%;">* {{\App\CPU\translate('Foto landscape untuk mendapatkan tampilan terbaik.')}}</small></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-1">
                                    <label
                                        class="d-block label-name">{{\App\CPU\translate('Foto_bangunan_tampak_depan')}}</label>
                                    <small style="color: red">* {{\App\CPU\translate('Foto horizontal akan terlihat
                                        lebih bagus sebagai foto utama kos Anda.')}}</small>
                                </div>
                                <div style="max-width:200px;">
                                    <div class="row" id="depan"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-1">
                                    <label
                                        class="d-block label-name">{{\App\CPU\translate('Foto_dalam_bangunan')}}</label>
                                    <small style="color: red">* {{\App\CPU\translate('Perlihatkan suasana di dalam
                                        dengan cahaya terang agar terlihat lebih jelas.')}}</small>
                                </div>
                                <div style="max-width:200px;">
                                    <div class="row" id="dalam"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-1">
                                    <label
                                        class="d-block label-name">{{\App\CPU\translate('Foto_tampak_dari_jalan')}}</label>
                                    <small style="color: red">* {{\App\CPU\translate('Lewat foto ini, tunjukkan
                                        lingkungan sekitar depan kos ke calon penyewa.')}}</small>
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
                                        @foreach ($rule as $r)
                                        <div class="col-6 mb-2">
                                            <div class="form-check" style="margin-left: 15px">
                                                <input class="form-check-input" name="aturan[]" type="checkbox"
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
                </div>
                {{--
                <div class="card mt-2 rest-part">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('General_info')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="name">{{\App\CPU\translate('Category')}}</label>
                                    <select class="js-example-basic-multiple form-control" name="category_id"
                                        onchange="getRequest('{{url('/')}}/seller/product/get-categories?parent_id='+this.value,'sub-category-select','select')"
                                        required>
                                        <option value="{{old('category_id')}}" selected disabled>
                                            ---{{\App\CPU\translate('Select')}}---</option>
                                        @foreach($cat as $c)
                                        <option value="{{$c['id']}}" {{old('name')==$c['id']? 'selected' : '' }}>
                                            {{$c['name']}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="name">{{\App\CPU\translate('Sub_category')}}</label>
                                    <select class="js-example-basic-multiple form-control" name="sub_category_id"
                                        id="sub-category-select"
                                        onchange="getRequest('{{url('/')}}/seller/product/get-categories?parent_id='+this.value,'sub-sub-category-select','select')">
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="name">{{\App\CPU\translate('Sub_sub_category')}}</label>
                                    <select class="js-example-basic-multiple form-control" name="sub_sub_category_id"
                                        id="sub-sub-category-select">

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="name">{{\App\CPU\translate('Brand')}}</label>
                                    <select
                                        class="js-example-basic-multiple js-states js-example-responsive form-control"
                                        name="brand_id" required>
                                        <option value="{{null}}" selected disabled>
                                            ---{{\App\CPU\translate('Select')}}---</option>
                                        @foreach($br as $b)
                                        <option value="{{$b['id']}}">{{$b['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="weight">{{\App\CPU\translate('Product_weight')}}</label>
                                    <input type="number" class="form-control" name="weight" required
                                        placeholder="in gram">
                                </div>
                                <div class="col-md-4">
                                    <label for="name">{{\App\CPU\translate('Unit')}}</label>
                                    <select class="js-example-basic-multiple form-control" name="unit">
                                        @foreach(\App\CPU\Helpers::units() as $x)
                                        <option value="{{$x}}" {{old('unit')==$x? 'selected' :''}}>{{$x}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-2 rest-part">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('Variations')}}</h4>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="colors">
                                        {{\App\CPU\translate('Colors')}} :
                                    </label>
                                    <label class="switch">
                                        <input type="checkbox" class="status" name="colors_active"
                                            value="{{old('colors_active')}}">
                                        <span class="slider round"></span>
                                    </label>
                                    <select
                                        class="js-example-basic-multiple js-states js-example-responsive form-control color-var-select"
                                        name="colors[]" multiple="multiple" id="colors-selector" disabled>
                                        @foreach (\App\Model\Color::orderBy('name', 'asc')->get() as $key => $color)
                                        <option value="{{ $color->code }}">
                                            {{$color['name']}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="attributes" style="padding-bottom: 3px">
                                        {{\App\CPU\translate('Attributes')}} :
                                    </label>
                                    <select
                                        class="js-example-basic-multiple js-states js-example-responsive form-control"
                                        name="choice_attributes[]" id="choice_attributes" multiple="multiple">
                                        @foreach (\App\Model\Attribute::orderBy('name', 'asc')->get() as $key => $a)
                                        <option value="{{ $a['id']}}">
                                            {{$a['name']}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12 mt-2 mb-2">
                                    <div class="customer_choice_options" id="customer_choice_options">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-2 rest-part">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('Product_price_&_stock')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">{{\App\CPU\translate('Unit_price')}}</label>
                                    <input type="number" min="0" value="0" step="0.01"
                                        placeholder="{{\App\CPU\translate('Unit_price')}}" name="unit_price"
                                        value="{{old('unit_price')}}" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">{{\App\CPU\translate('Purchase_price')}}</label>
                                    <input type="number" min="0" value="0" step="0.01"
                                        placeholder="{{\App\CPU\translate('Purchase_price')}}" name="purchase_price"
                                        value="{{old('purchase_price')}}" class="form-control" required>
                                </div>
                            </div>

                            <div class="row pt-4">
                                <div class="col-md-6">
                                    <label class="control-label">{{\App\CPU\translate('Tax')}}</label>
                                    <label class="badge badge-info">{{\App\CPU\translate('Percent')}} ( % )</label>
                                    <input type="number" min="0" value="0" step="0.01"
                                        placeholder="{{\App\CPU\translate('Tax')}}" name="tax" value="{{old('tax')}}"
                                        class="form-control">
                                    <input name="tax_type" value="percent" style="display: none">
                                </div>

                                <div class="col-md-4">
                                    <label class="control-label">{{\App\CPU\translate('Discount')}}</label>
                                    <input type="number" min="0" value="0" step="0.01"
                                        placeholder="{{\App\CPU\translate('Discount')}}" name="discount"
                                        value="{{old('discount')}}" class="form-control">
                                </div>
                                <div class="col-md-2" style="padding-top: 30px;">
                                    <select class="form-control js-select2-custom" name="discount_type">
                                        <option value="flat">{{\App\CPU\translate('Flat')}}</option>
                                        <option value="percent">{{\App\CPU\translate('Percent')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="sku_combination" id="sku_combination">

                            </div>
                            <div class="row pt-4">
                                <div class="col-md-6" id="quantity">
                                    <label class="control-label">{{\App\CPU\translate('total')}}
                                        {{\App\CPU\translate('Quantity')}}</label>
                                    <input type="number" min="0" value="0" step="1"
                                        placeholder="{{\App\CPU\translate('Quantity')}}" name="current_stock"
                                        value="{{old('current_stock')}}" class="form-control" required>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="card mt-2 mb-2 rest-part">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('seo_section')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="control-label">{{\App\CPU\translate('Meta_title')}}</label>
                                <input type="text" name="meta_title" placeholder="" class="form-control">
                            </div>

                            <div class="col-md-8 mb-4">
                                <label class="control-label">{{\App\CPU\translate('Meta_description')}}</label>
                                <textarea rows="10" type="text" name="meta_description" class="form-control"></textarea>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <label>{{\App\CPU\translate('Meta_image')}}</label>
                                </div>
                                <div class="border border-dashed">
                                    <div class="row" id="meta_img"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}



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
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('public/assets/select2/js/select2.min.js')}}"></script>
<script src="{{asset('public/assets/back-end/js/spartan-multi-image-picker.js')}}"></script>
<script>
    $(document).ready(function(){
        $('.js-example-basic-single').select2();
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
                        $('select[name="city"]').empty();
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
                        $('select[name="disrict"]').empty();
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



    $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'images[]',
                maxCount: 4,
                rowHeight: 'auto',
                groupClassName: 'col-6',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/back-end/img/400x400/img2.jpg')}}',
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

            $("#depan").spartanMultiImagePicker({
                fieldName: 'depan',
                maxCount: 1,
                rowHeight: 'auto',
                groupClassName: 'col-12',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/back-end/img/400x400/img2.jpg')}}',
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
            });$("#dalam").spartanMultiImagePicker({
                fieldName: 'dalam',
                maxCount: 1,
                rowHeight: 'auto',
                groupClassName: 'col-12',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/back-end/img/400x400/img2.jpg')}}',
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
                    image: '{{asset('public/assets/back-end/img/400x400/img2.jpg')}}',
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

            $("#meta_img").spartanMultiImagePicker({
                fieldName: 'meta_image',
                maxCount: 1,
                rowHeight: '280px',
                groupClassName: 'col-12',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/back-end/img/400x400/img2.jpg')}}',
                    width: '90%',
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
                    url: '{{route('seller.property.store')}}',
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
                            toastr.success('{{\App\CPU\translate('Property updated successfully!')}}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            $('#product_form').submit();
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
