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
            <li class="breadcrumb-item" aria-current="page"><a
                    href="{{route('admin.product.list', ['type' => 'in_house'])}}">{{\App\CPU\translate('Room')}}</a>
            </li>
            <li class="breadcrumb-item">{{\App\CPU\translate('Add_new')}}</li>
        </ol>
    </nav>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">

            <form class="product-form" action="{{route('admin.product.store')}}" method="post"
                enctype="multipart/form-data" style="text-align: {{Session::get('direction') === " rtl" ? 'right'
                : 'left' }};" id="product_form">
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
                                        <option value="">-- Pilih property yang akan ditambahkan kamar --</option>
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
                                    <select class="form-control" id="tipe" name="type">
                                        <option value="">-- Pilih tipe kamar --</option>
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

                <div class="card mt-2 rest-part">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('Foto_kamar')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            {{-- <div class="col-md-12 mb-4">
                                <label class="control-label">{{\App\CPU\translate('Youtube video link')}}</label>
                                <small class="badge badge-soft-danger"> ( {{\App\CPU\translate('optional, please provide
                                    embed link not direct link')}}. )</small>
                                <input type="text" name="video_link"
                                    placeholder="EX : https://www.youtube.com/embed/5R06LRdUCSE" class="form-control"
                                    required>
                            </div> --}}

                            <div class="col-12">
                                <div class="form-group">
                                    <label
                                        class="d-block text-dark label-name">{{\App\CPU\translate('Foto_kamar_dari_berbagai_sisi')}}
                                        <small style="color: red">* {{\App\CPU\translate('Foto landscape untuk mendapatkan tampilan terbaik.')}}</small></label>
                                    <small class="text-info">* {{\App\CPU\translate('Urutan foto kamar: Depan kamar -
                                        Dalam kamar - Kamar mandi dalam. (Boleh menambahkan foto kamar lain)')}}</small>
                                </div>
                                <div class="p-2 border border-dashed" style="max-width:430px;">
                                    <div class="row" id="coba"></div>
                                </div>
                            </div>

                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">{{\App\CPU\translate('Upload_thumbnail')}}</label><small
                                        style="color: red">* ( {{\App\CPU\translate('ratio 1:1')}} )</small>
                                </div>
                                <div style="max-width:200px;">
                                    <div class="row" id="thumbnail"></div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="card mt-2 rest-part">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('Ketersediaan_kamar')}}</h4>
                        <small>{{\App\CPU\translate('Mohon_masukan_keterangan_dan_lantai_pada_tiap_kamar')}}</small>
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
                                                    id="size1" value="3x3">
                                                <label class="form-check-label room-size" for="size1">
                                                    <span class="title-radio m-auto">3 X 3</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-check p-0">
                                                <input class="form-check-input hideBx" type="radio" name="size"
                                                    id="size2" value="3x4">
                                                <label class="form-check-label room-size" for="size2">
                                                    <div class="title-radio m-auto">3 X 4</div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-check p-0">
                                                <input class="form-check-input hideBx" type="radio" name="size"
                                                    id="size3" value="{{old('size')}}">
                                                <label class="form-check-label room-size" for="size3">
                                                    <div class="title-radio m-auto">{{ \App\CPU\translate('Lainnya') }}
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="customSize" class="customSize mt-3">
                                        <small>{{ \App\CPU\translate('Ukuran_kamar_lainnya') }}:</small>
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

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="input-label label-name mb-0"
                                        for="name">{{\App\CPU\translate('Berapa_jumlah_total_kamar')}} ?</label>
                                    <small>{{\App\CPU\translate('Jumlah_kamar_harus_di_antara_1-500_kamar.')}}</small>
                                    <input type="number" min="0" max="500" value="0" step="1" name="total" id="total"
                                        class="form-control" required>
                                    <div class="err d-flex justify-content-center">
                                        <span id="totalErr" class="text-danger d-none">Jumlah total kamar tidak boleh
                                            kosong.</span>
                                    </div>
                                </div>

                                {{-- <div class="col-md-6" id="quantiti">
                                    <label class="input-label label-name mb-4"
                                        for="name">{{\App\CPU\translate('Berapa_jumlah_kamar_tersedia')}} ?</label>
                                    <input type="number" min="0" value="0" step="1" id="available" name="available"
                                        value="" class="form-control" required>
                                    <small id="avaiErr" class="text-danger d-none">Jumlah kamar tersedia tidak boleh
                                        lebih dari total kamar.</small>
                                </div> --}}
                            </div>

                            <div class="row pt-4">
                                <div class="col-md-12">
                                    <label class="input-label label-name mb-0 d-block"
                                        for="name">{{\App\CPU\translate('Data_ketersediaan_kamar')}}</label>
                                    <small>{{\App\CPU\translate('Mohon masukkan keterangan nomor/nama pada tiap data
                                        kamar.')}}</small>
                                    <div class="row justify-content-center">
                                        <input type="hidden" id="roomData" name="rooms[]">
                                        <div class="col-md-8">
                                            <button id="btnKamar" class="btn mt-3 w-100 btn-outline-success d-block" disabled
                                                onclick="addRoom()" data-toggle="modal" data-target="#staticBackdrop"
                                                type="button">{{\App\CPU\translate('Atur_Ketersediaan_Kamar')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Room-->
                            <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
                                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">
                                                {{\App\CPU\translate('Data_ketersediaan_kamar')}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div id="modal-room" class="modal-body">

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" onclick="removeRoom()"
                                                data-dismiss="modal">{{\App\CPU\translate('Tutup')}}</button>
                                            <button type="button" onclick="addKamar()"
                                                class="btn btn-primary">{{\App\CPU\translate('Simpan')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-4">

                            </div>
                        </div>

                    </div>
                </div>

                <div class="card mt-2 rest-part">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('Harga_&_stock_kamar')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">{{\App\CPU\translate('Harga_kamar')}}/ bulan</label>
                                    <input type="number" min="0" value="0" step="0.01"
                                        placeholder="{{\App\CPU\translate('Harga_kamar')}}" name="unit_price"
                                        value="{{old('unit_price')}}" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="attributes" style="padding-bottom: 3px">
                                        {{\App\CPU\translate('Paket')}} :
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
                                    <div class="customer_choice_options" id="customer_choice_options"></div>
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
                            <div class="pt-4 col-12 sku_combination" id="sku_combination">
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
    </div>
</div>
@endsection

@push('script')
<script src="{{asset('public/assets/back-end')}}/js/tags-input.min.js"></script>
<script src="{{ asset('public/assets/select2/js/select2.min.js')}}"></script>
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

    // $('#cus2').on('change', function(){
    //     var h =
    // })
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

            $("#thumbnail").spartanMultiImagePicker({
                fieldName: 'image',
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
            $('#customer_choice_options').append('<div class="row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' + i + '"><input type="text" class="form-control" name="choice[]" value="' + n + '" placeholder="{{trans('Choice Title') }}" readonly></div><div class="col-lg-9"><input type="text" class="form-control" name="choice_options_' + i + '[]" placeholder="{{trans('Enter choice values') }}" data-role="tagsinput" onchange="update_sku()"></div></div>');

            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }


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
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                }
            });
        }

        $(document).ready(function () {
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
                    url: '{{route('admin.product.store')}}',
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
                            toastr.success('{{\App\CPU\translate('Kamar_berhasil_ditambahkan!')}}', {
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
