@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Product Edit'))

@push('css_or_js')
<link href="{{asset('public/assets/back-end/css/tags-input.min.css')}}" rel="stylesheet">
<link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<!-- Page Heading -->
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page"><a
                    href="{{route('admin.product.list', ['in_house', ''])}}">{{\App\CPU\translate('Rooms')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('Edit')}}</li>
        </ol>
    </nav>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <form class="product-form" action="{{route('admin.product.update',$product->id)}}" method="post"
                style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};"
                enctype="multipart/form-data" id="product_form">
                @csrf

                <div class="card">
                    <div class="card-header">
                        @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
                        @php($language = $language->value ?? null)
                        @php($default_lang = 'en')

                        @php($default_lang = json_decode($language)[0])
                        <h4>{{\App\CPU\translate('info_kamar')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label label-name"
                                        for="tipe">{{\App\CPU\translate('Property')}}</label>
                                    <select class="form-control" id="tipe" name="kost_id">
                                        <option value="{{ $product->kost_id }}">{{ $product->kost->name }}</option>
                                        @foreach ($kost as $k)
                                        <option value="{{ $k->id }}">{{ $k->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label label-name"
                                        for="tipe">{{\App\CPU\translate('tipe_kamar')}}</label>
                                    <select class="form-control capitalize" id="tipe" name="type">
                                        <option class="capitalize" value="{{ $product->type }}">{{ $product->type }}
                                        </option>
                                        <option value="standard">Standard</option>
                                        <option value="vip">VIP</option>
                                        <option value="vvip">VVIP</option>
                                        <option value="eksekutive">Eksekutive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label label-name"
                                        for="tipe">{{\App\CPU\translate('Fasilitas_kamar')}}</label>
                                    <div class="row">
                                        @foreach ($fas as $f)
                                        <div class="col-6 mb-2">
                                            <div class="form-check" style="margin-left: 15px">
                                                <input class="form-check-input" name="fasilitas[]" type="checkbox"
                                                    value="{{ $f->id }}" id="flexCheckDefault" @foreach ($fasi as $a)
                                                    @if ($a==$f->id) checked
                                                @endif @endforeach>
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

                <div class="card mt-2 rest-part">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('Foto_kamar')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label
                                        class="d-block label-name">{{\App\CPU\translate('Foto_kamar_dari_berbagai_sisi')}}
                                        <small style="color: red">* {{\App\CPU\translate('Foto landscape untuk mendapatkan tampilan terbaik.')}}</small></label></label>
                                    <small class="text-info">* {{\App\CPU\translate('Urutan foto kamar: Depan
                                        kamar - Dalam kamar - Kamar mandi dalam. (Boleh menambahkan foto kamar
                                        lain)')}}</small>
                                </div>
                                <div class="p-2 border border-dashed" style="max-width:430px;">
                                    <div class="row" id="coba">
                                        @foreach (json_decode($product->images) as $key => $photo)
                                        <div class="col-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <img style="width: 100%" height="auto"
                                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                        src="{{asset("storage/product/$photo")}}"
                                                        alt="Product image">
                                                    <a href="{{route('admin.product.remove-image',['id'=>$product['id'],'name'=>$photo])}}"
                                                        class="btn btn-danger btn-block">{{\App\CPU\translate('Remove')}}</a>

                                                </div>
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
                        <h4>{{\App\CPU\translate('Ukuran_kamar')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label label-name mb-0"
                                        for="name">{{\App\CPU\translate('Berapa_ukuran_kamarnya')}} ?</label>
                                    <small>Pilih ukuran kamar:</small>
                                    <div class="row mt-3">
                                        <div class="col-4">
                                            <div class="form-check p-0">
                                                <input class="form-check-input hideBx" type="radio" name="size"
                                                    id="size1" value="3x3" {{ ($product->size=="3x3")? "checked" : "" }}>
                                                <label class="form-check-label room-size" for="size1">
                                                    <span class="title-radio m-auto">3 X 3</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-check p-0">
                                                <input class="form-check-input hideBx" type="radio" name="size"
                                                    id="size2" value="3x4" {{ ($product->size=="3x4")? "checked" : "" }}>
                                                <label class="form-check-label room-size" for="size2">
                                                    <div class="title-radio m-auto">3 X 4</div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-check p-0">
                                                <input class="form-check-input hideBx" type="radio" name="size"
                                                    id="size3" value="{{$product->size}}" {{ ($product->size!="3x3") && ($product->size!="3x4")? "checked" : "" }}>
                                                <label class="form-check-label room-size" for="size3">
                                                    <div class="title-radio m-auto">{{ \App\CPU\translate('Lainnya') }}
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="customSize" class="customSize mt-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="">Ukuran sekarang</label>
                                                    <input type="text" class="form-control" value="{{ $product->size }}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <small>{{ \App\CPU\translate('Ubah_ukuran_kamar_lainnya') }}:</small>
                                        <div class="row mt-2">

                                            <div class="col-5 pr-0">
                                                <div class="input-group">
                                                    <input type="number" id="cus1" name="customSize1" class="form-control"
                                                        aria-describedby="lebar">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="lebar">M</span>
                                                    </div>
                                                </div>
                                                <div class="err d-flex justify-content-center">
                                                    <small id="sizeErr1" class="text-danger d-none">Ukuran ini tidak boleh kosong.</small>
                                                </div>
                                            </div>
                                            <div class="col-1 d-flex p-0">
                                                <span class="m-auto text-dark"> X </span>
                                            </div>
                                            <div class="col-5 pl-0">
                                                <div class="input-group">
                                                    <input type="number" id="cus2" name="customSize2" class="form-control"
                                                        aria-describedby="tinggi">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="tinggi">M</span>
                                                    </div>
                                                </div>
                                                <div class="err d-flex justify-content-center">
                                                    <small id="sizeErr2" class="text-danger d-none">Ukuran ini tidak boleh kosong.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card mt-2 rest-part">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('Harga_kamar')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">{{\App\CPU\translate('Harga_kamar')}}/ bulan</label>
                                    <input type="number" min="0" step="0.01"
                                        placeholder="{{\App\CPU\translate('Harga_kamar')}}" name="unit_price"
                                        value="{{\App\CPU\Convert::default($product->unit_price)}}" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="attributes" style="padding-bottom: 3px">
                                        {{\App\CPU\translate('Attributes')}} :
                                    </label>
                                    <select
                                        class="js-example-basic-multiple js-states js-example-responsive form-control"
                                        name="choice_attributes[]" id="choice_attributes" multiple="multiple">
                                        @foreach (\App\Model\Attribute::orderBy('name', 'asc')->get() as $key => $a)
                                            @if($product['attributes']!='null')
                                                <option
                                                    value="{{ $a['id']}}" {{in_array($a->id,json_decode($product['attributes'],true))?'selected':''}}>
                                                    {{$a['name']}}
                                                </option>
                                            @else
                                                <option value="{{ $a['id']}}">{{$a['name']}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2 mb-2">
                                    <div class="customer_choice_options" id="customer_choice_options">
                                        @include('admin-views.product.partials._choices',['choice_no'=>json_decode($product['attributes']),'choice_options'=>json_decode($product['choice_options'],true)])
                                    </div>
                                </div>
                            </div>

                            <div class="row pt-4">
                                <div class="col-md-6">
                                    <label class="control-label">{{\App\CPU\translate('Tax')}}</label>
                                    <label class="badge badge-info">{{\App\CPU\translate('Percent')}} ( % )</label>
                                    <input type="number" min="0" value="{{ $product->tax }}" step="0.01"
                                        placeholder="{{\App\CPU\translate('Tax')}}" name="tax" value="{{old('tax')}}"
                                        class="form-control">
                                    <input name="tax_type" value="percent" style="display: none">
                                </div>

                                <div class="col-md-4">
                                    <label class="control-label">{{\App\CPU\translate('Discount')}}</label>
                                    <input type="number" min="0" value="{{ $product->discount_type=='flat'?\App\CPU\Convert::default($product->discount): $product->discount}}" step="0.01"
                                        placeholder="{{\App\CPU\translate('Discount')}}" name="discount"
                                        value="{{old('discount')}}" class="form-control">
                                </div>
                                <div class="col-md-2" style="padding-top: 30px;">
                                    <select class="form-control js-select2-custom" name="discount_type">
                                        <option value="flat" {{$product['discount_type']=='flat'?'selected':''}}>{{\App\CPU\translate('Flat')}}</option>
                                        <option value="percent" {{$product['discount_type']=='percent'?'selected':''}}>{{\App\CPU\translate('Percent')}}</option>
                                    </select>
                                </div>
                                <div class="col-12 pt-4 sku_combination" id="sku_combination">
                                    @include('admin-views.product.partials._edit_sku_combinations',['combinations'=>json_decode($product['variation'],true)])
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="card card-footer">
                    <div class="row">
                        <div class="col-md-12" style="padding-top: 20px">
                            @if($product->request_status == 2)
                            <button type="button" onclick="check()" class="btn btn-primary">{{\App\CPU\translate('Update
                                & Publish')}}</button>
                            @else
                            <button type="button" onclick="check()"
                                class="btn btn-primary">{{\App\CPU\translate('Update')}}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script_2')
<script src="{{asset('public/assets/back-end')}}/js/tags-input.min.js"></script>
<script src="{{asset('public/assets/back-end/js/spartan-multi-image-picker.js')}}"></script>
<script>
    $('#cus1').on('change', function(){
        var h = $(this).val()
        var w = $('#cus2').val()
        if(h == 0){
            $('#sizeErr1').removeClass('d-none')
            $('#cus1').attr('style', 'border: 2px solid red')
        }else {
            $('#sizeErr1').addClass('d-none')
            $('#cus1').attr('style', '')
        }
        if(h != 0 || h != ''){
            if(w != 0 || w != ''){
                putVal(h + 'x' + w)
            }
        }
    })

    function putVal(h){
        var h = h
        console.log('val', h)
        $('#size3').val(h);
    }

    $('#cus2').on('change', function(){
        var h = $(this).val()
        var w = $('#cus1').val()

        if(w == 0 || w == ''){
            $('#sizeErr1').removeClass('d-none')
            $('#cus1').attr('style', 'border: 2px solid red')
        }else {
            $('#sizeErr1').addClass('d-none')
            $('#cus1').attr('style', '')
        }

        if(h == 0){
            $('#sizeErr2').removeClass('d-none')
            $('#cus2').attr('style', 'border: 2px solid red')
        }else {
            $('#sizeErr2').addClass('d-none')
            $('#cus2').attr('style', '')

        }

        if(h != 0 || h != ''){
            if(w != 0 || w != ''){
                putVal(w + 'x' + h)
            }
        }
    })

    function addRoom(){
        var jumlah = $('#total').val()
        for(i = 0; i < jumlah; i++){
            $('#modal-room').append('<div class="card mb-2 room-input"><form action="" id="form-room"><div class="card-header"><h5>' + (i+1) + ' dari ' + jumlah + ' kamar </h5></div><div class="card-body"><div class="form-group"><label class="label-name text-dark" style="font-size: 16px">Nomor / Nama Kamar ?</label><input type="text" class="form-control" name="nomor" id="nomor' + (i+1) +'"></div><div class="form-check"><input class="form-check-input" name="isi' + (i+1) +'" type="checkbox" value="1" id="isi' + (i+1) +'"><label class="form-check-label" for="isi' + (i+1) +'">Sudah Berpenghuni</label></div></div></form></div>')
        };
    }

    function removeRoom(){
        $('.room-input').remove()
    }

    $('#total').on('change', function(){
        var total = $(this).val()
        if(total == 0){
            $('#btnKamar').attr('disabled', 'true')
            $('#totalErr').removeClass('d-none')
            $('#total').attr('style', 'border: 2px solid red')

        }else {
            $('#totalErr').addClass('d-none')
            $('#total').attr('style', '')
            $('#btnKamar').removeAttr('disabled')
        }
    })

    function addKamar(){
        var jumlah = $('#total').val()
        var kamar = [];
        for(i = 1; i <= jumlah; i++){
            var no = $('#nomor' +  i).val()
            var isi = $('input[id=isi' + i +']:checked').length;
            var room = {
                'nomor' : no,
                'isi' :  isi
            }
            kamar.push(room)
        }
        $('#roomData').val(JSON.stringify(kamar));
        $('#staticBackdrop').modal('hide')
    }

    $('#available').on('change', function(){
        var total = $('#total').val()
        var avai = $(this).val()
        if(total < avai){
            $('#btnKamar').attr('disabled', 'true')
            $('#avaiErr').removeClass('d-none')
            $('#available').attr('style', 'border: 2px solid red')

        }else {
            $('#avaiErr').addClass('d-none')
            $('#available').attr('style', '')
            $('#btnKamar').removeAttr('disabled')
        }
    })

    $(document).ready(function(){
        $('#size1').click(function () {
            $('#customSize').removeClass('showBox')
        });

        $('#size2').click(function () {
            $('#customSize').removeClass('showBox')
        });

        $('#size3').click(function () {
            $('#customSize').addClass('showBox')
        });
    });

    var imageCount = {{4-count(json_decode($product->images))}};
        var thumbnail = '{{\App\CPU\ProductManager::product_image_path('thumbnail').'/'.$product->thumbnail??asset('public/assets/back-end/img/400x400/img2.jpg')}}';
        $(function () {
            if (imageCount > 0) {
                $("#coba").spartanMultiImagePicker({
                    fieldName: 'images[]',
                    maxCount: imageCount,
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
            }

            $("#thumbnail").spartanMultiImagePicker({
                fieldName: 'image',
                maxCount: 1,
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

            $("#meta_img").spartanMultiImagePicker({
                fieldName: 'meta_image',
                maxCount: 1,
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

        $('input[name="colors_active"]').on('change', function () {
            if (!$('input[name="colors_active"]').is(':checked')) {
                $('#colors-selector').prop('disabled', true);
            } else {
                $('#colors-selector').prop('disabled', false);
            }
        });

        $('#choice_attributes').on('change', function () {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function () {
                //console.log($(this).val());
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
        });

        function add_more_customer_choice_option(i, name) {
            let n = name.split(' ').join('');
            $('#customer_choice_options').append('<div class="row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' + i + '"><input type="text" class="form-control" name="choice[]" value="' + n + '" placeholder="{{\App\CPU\translate('Choice Title') }}" readonly></div><div class="col-lg-9"><input type="text" class="form-control" name="choice_options_' + i + '[]" placeholder="{{\App\CPU\translate('Enter choice values') }}" data-role="tagsinput" onchange="update_sku()"></div></div>');
            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

        setTimeout(function () {
            $('.call-update-sku').on('change', function () {
                update_sku();
            });
        }, 2000)

        $('#colors-selector').on('change', function () {
            update_sku();
        });

        $('input[name="unit_price"]').on('keyup', function () {
            update_sku();
        });

        function update_sku() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '{{route('admin.product.sku-combination')}}',
                data: $('#product_form').serialize(),
                success: function (data) {
                    $('#sku_combination').html(data.view);
                    update_qty();
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                }
            });
        }

        $(document).ready(function () {
            setTimeout(function () {
                let category = $("#category_id").val();
                let sub_category = $("#sub-category-select").attr("data-id");
                let sub_sub_category = $("#sub-sub-category-select").attr("data-id");
                getRequest('{{url('/')}}/admin/product/get-categories?parent_id=' + category + '&sub_category=' + sub_category, 'sub-category-select', 'select');
                getRequest('{{url('/')}}/admin/product/get-categories?parent_id=' + sub_category + '&sub_category=' + sub_sub_category, 'sub-sub-category-select', 'select');
            }, 100)
            // color select select2
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

<script>
    function check() {
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
                url: '{{route('admin.product.update',$product->id)}}',
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
                        toastr.success('kamar berhasil di update!', {
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
