@extends('layouts.back-end.app-seller')

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
    .label-name{
        font-size: 14px !important;
    }
</style>

<div class="content container-fluid">
    <!-- Page Heading -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a
                    href="{{route('seller.dashboard.index')}}">{{\App\CPU\translate('Dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page"><a
                    href="{{route('seller.jobs.list')}}">{{\App\CPU\translate('Jobs')}}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Edit')}}</li>
        </ol>
    </nav>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <form class="product-form" action="{{route('seller.jobs.update',$product->id)}}" method="post"
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
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label label-name mb-0"
                                            for="name">{{\App\CPU\translate('Nama_tempat_usaha')}} / {{\App\CPU\translate('Nama_perusahaan')}}</label>
                                        <input type="text" name="company_name" id="name" class="form-control"
                                            value="{{ $product->company_name }}" required>
                                    </div>
                                </div>
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
                                            for="cat">{{\App\CPU\translate('Catatan_alamat')}} <small>(nama jalan, nomor kantor/usaha)</small></label>
                                        <textarea class="form-control w-100" name="noteAddress" cols="30"
                                            rows="3">{{ $product->note_address }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label label-name mb-0"
                                            for="cat">{{\App\CPU\translate('Lokasi_penempatan_kerja')}} <small>(Alamat penempatan)</small></label>
                                        <textarea class="form-control w-100" name="penempatan" cols="30"
                                            rows="3">{{ $product->penempatan }}</textarea>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="onsite" type="checkbox">
                                            Kandidat harus bersedia ditempatkan di sini
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 d-flex flex-column align-items-center">
                                    <div class="form-group mb-1">
                                        <label
                                            class="d-block label-name">{{\App\CPU\translate('Logo_perusahaan')}}</label>
                                    </div>
                                    <div style="max-width:200px;">
                                        <div class="row d-flex" id="depan">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Info pekerjaan -->
                <div class="card mt-2 rest-part">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('Input_info_pekerjaan')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label label-name mb-0"
                                            for="name">{{\App\CPU\translate('Nama_pekerjaan')}}</label>
                                        <input type="text" name="name" value="{{ $product->name }}" id="name" class="form-control"
                                            placeholder="Contoh : Penjaga toko" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label label-name mb-0"
                                            for="cat">{{\App\CPU\translate('Spesialisasi_pekerjaan')}}</label>
                                        <select class="form-control" id="cat" name="keahlian">
                                            <option value="">-- Pilih jenis keahlian --</option>
                                            <option value="Informatika" {{ $product->keahlian == 'Informatika' ? 'Selected': '' }}>Informatika</option>
                                            <option value="Akutansi" {{ $product->keahlian == 'Akutansi' ? 'Selected': '' }}>Akutansi</option>
                                            <option value="Desainer" {{ $product->keahlian == 'Desainer' ? 'Selected': '' }}>Desainer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label label-name"
                                            for="cat">{{\App\CPU\translate('Pendidikan_minimal')}}</label>
                                        <select class="js-example-basic-single form-control" name="pendidikan" id="ptn">
                                            <option value="">-- Pilih pendidikan --</option>
                                            <option value="SD/MI" {{ $product->pendidikan == 'SD/MI' ? 'Selected': '' }}>SD/MI</option>
                                            <option value="SMP/MTs" {{ $product->pendidikan == 'SMP/MTs' ? 'Selected': '' }}>SMP/MTs</option>
                                            <option value="SMA/MA/SMK" {{ $product->pendidikan == 'SMA/MA/SMK' ? 'Selected': '' }}>SMA/MA/SMK</option>
                                            <option value="D3" {{ $product->pendidikan == 'D3' ? 'Selected': '' }}>D3</option>
                                            <option value="S1" {{ $product->pendidikan == 'S1' ? 'Selected': '' }}>S1</option>
                                            <option value="S2" {{ $product->pendidikan == 'S2' ? 'Selected': '' }}>S2</option>
                                            <option value="S3" {{ $product->pendidikan == 'S3' ? 'Selected': '' }}>S3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label label-name mb-0"
                                            for="cat">{{\App\CPU\translate('Status_pekerjaan')}}</label>
                                        <select class="js-example-basic-single form-control" name="status" id="ptn">
                                            <option value="">-- Pilih status pekerjaan --</option>
                                            <option value="parttime" {{ $product->status_employe == 'parttime' ? 'Selected': '' }}>PartTime</option>
                                            <option value="fulltime" {{ $product->status_employe == 'fulltime' ? 'Selected': '' }}>FullTime</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <input type="hidden" name="lang[]" value="en">
                                    <div class="form-group">
                                        <label class="input-label label-name mb-0"
                                            for="tipe">{{\App\CPU\translate('Deskripsi_pekerjaan')}}</label>
                                        <small>Jelaskan lebih lengkap tentang pekerjaan & tanggung jawab</small>
                                        <textarea name="deskripsi" class="editor textarea" cols="30"
                                                rows="10" required>{{ $product->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label label-name mb-0"
                                            for="name">{{\App\CPU\translate('Gaji')}}</label>
                                        <input type="number" name="gaji" class="form-control" value="{{ $product->gaji }}" required>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="hide" type="checkbox">
                                            Jangan tampilkan gaji pada kandidat
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label label-name mb-0"
                                            for="cat">{{\App\CPU\translate('Satuan_gaji')}}</label>
                                        <select class="js-example-basic-single form-control" name="satuan" id="ptn">
                                            <option value="">-- Pilih satuan gaji --</option>
                                            <option value="hari" {{ $product->satuan_gaji == 'hari' ? 'Selected': '' }}>per Hari</option>
                                            <option value="bulan" {{ $product->satuan_gaji == 'bulan' ? 'Selected': '' }}>per Bulan</option>
                                            <option value="project" {{ $product->satuan_gaji == 'project' ? 'Selected': '' }}>per Project</option>
                                        </select>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-2 rest-part">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('Input_info_penanggung_jawab')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label label-name mb-0"
                                        for="name">{{\App\CPU\translate('Nama_penanggung_jawab')}}</label>
                                    <input type="text" name="penanggung" value="{{ $product->penanggung_jwb }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label label-name mb-0"
                                        for="name">{{\App\CPU\translate('Nomor_telepon')}}</label>
                                    <input type="number" name="hp" value="{{ $product->hp_penanggung_jwb }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label label-name mb-0"
                                        for="name">{{\App\CPU\translate('Email_penanggung_jawab')}}</label>
                                    <input type="email" name="email" value="{{ $product->email_penanggung_jwb }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label label-name mb-0"
                                        for="name">{{\App\CPU\translate('Lowongan_ditutup')}}</label>
                                    @if ($product->expire != NULL)
                                    @php($date = Carbon\Carbon::parse($product->expire))
                                    <input type="date" name="expire" class="form-control" value="{{ $date->format('Y-m-d') }}" required>
                                    @else
                                    <input type="date" name="expire" class="form-control" required>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-2 rest-part">
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
                            console.log('prov', $prov)
                            kota = value
                            id = key
                        // // perhtikan dimana kita akan menampilkan data select nya, di sini saya memberi name select kota adalah kota_id
                        $('select[name="city"]').append(`<option value="${id}" if($prov == id){'selected'}>
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
                        $('select[name="district"]').append(`<option value="${kota}" if($city == $kota){'selected'}>
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

        var depan = '{{asset('storage/jobs').'/'.$product->logo ?? asset('public/assets/back-end/img/400x400/img2.jpg')}}';
        $(function () {
            $("#depan").spartanMultiImagePicker({
                fieldName: 'logo',
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
                url: '{{route('seller.jobs.update',$product->id)}}',
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
                        // toastr.success('{{\App\CPU\translate('kamar_berhassil_di_update!')}}', {
                        //     CloseButton: true,
                        //     ProgressBar: true
                        // });
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
