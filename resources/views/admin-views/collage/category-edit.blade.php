@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Category'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item"><a href="{{route('admin.collage.list')}}">{{\App\CPU\translate('Collage_list')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('collage')}}</li>
            </ol>
        </nav>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('collage_form')}}
                    </div>
                    <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <form action="{{route('admin.collage.update',[$category['id']])}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
                            @php($language = $language->value ?? null)
                            @php($default_lang = 'en')

                            @php($default_lang = json_decode($language)[0])
                            <div class="row">
                                <div class="col-6">
                                        <div class="form-group lang_form"
                                             id="en-form">
                                            <label class="input-label">{{\App\CPU\translate('name')}}</label>
                                            <input type="text" name="name"
                                                   value="{{ $category['name'] }}"
                                                   class="form-control"
                                                   placeholder="{{\App\CPU\translate('nama')}} {{\App\CPU\translate('kampus')}}" required>
                                        </div>
                                </div>
                                <div class="col-6">
                                        <div class="form-group lang_form"
                                             id="en-short">
                                            <label class="input-label">{{\App\CPU\translate('singkatan')}}</label>
                                            <input type="text" name="singkatan"
                                                   value="{{ $category['short'] }}"
                                                   class="form-control"
                                                   placeholder="{{\App\CPU\translate('Singkatan')}} {{\App\CPU\translate('kampus')}}" required>
                                        </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group lang_form">
                                        <label class="input-label mb-0"
                                        for="cat">{{\App\CPU\translate('Provinsi')}}</label>
                                        <select class="form-control" id="prov" name="province">
                                            <option value="">-- Pilih provinsi kampus--</option>
                                            @foreach ($provs as $key => $val)
                                            <option value="{{ $key }}" {{ ($category['province_id'] == $key)? 'selected': '' }}>{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{-- {{ dd($product) }} --}}
                                        <label class="input-label mb-0"
                                            for="cat">{{\App\CPU\translate('Kabupaten_/_Kota')}}</label>
                                        <select class="form-control" id="city" name="city">
                                            <option value="{{ $category->city_id }}" selected>{{ $category->city ? $category->city->name : '-- Pilih kota kampus --' }}</option>
                                        </select>
                                    </div>
                                </div>
                                <!--image upload only for main category-->
                                @if($category['parent_id']==0)
                                    <div class="col-6 from_part_2">
                                        <label>{{\App\CPU\translate('image')}}</label><small style="color: red">
                                            ( {{\App\CPU\translate('ratio')}} 3:1 )</small>
                                        <div class="custom-file" style="text-align: left">
                                            <input type="file" name="image" id="customFileEg1"
                                                   class="custom-file-input"
                                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label"
                                                   for="customFileEg1">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                        </div>
                                    </div>
                                    <div class="col-12 from_part_2">
                                        <div class="form-group">
                                            <hr>
                                            <center>
                                                <img style="width: 30%;border: 1px solid; border-radius: 10px;"
                                                     id="viewer"
                                                     src="{{asset('storage/collage')}}/{{$category['logo']}}"
                                                     alt=""/>
                                            </center>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-primary">{{\App\CPU\translate('update')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

    <script>
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

        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
@endpush
