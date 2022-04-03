@extends('layouts.front-end.app')

@section('title',$product['name'])

@push('css_or_js')
    <meta name="description" content="{{$product->slug}}">
@endpush
<style>
    .header-container{
        background-image: url(/assets/front-end/img/pattern.jpg);
        background-size: contain;
        background-repeat: repeat-x;
    }
    .header-shadow{
        background-color:  rgba(0,0,0,.5);
        width: 100%
    }
    .logo-cv{
        float: left;
    }
    .logo-cv img{
        border-radius: 7px;
        box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%), 0 6px 20px 0 rgb(0 0 0 / 19%);
        height: 55px;
        margin: 10px 10px 10px 0;
        width: 55px;
    }
    .company_name{
        font-size: 25px;
        font-weight: 700;
        margin-bottom: 5px;
        margin-top: 5px;
        max-width: 100%;
        color: #fff;
    }
    .address-cv{
        color: #fff;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .job-title h1{
        font-size: 28px;
        font-weight: 600;
        margin-top: 20px;
        margin-bottom: 10px;
    }
    label.status-employe{
        background-color: #24b400;
        padding: 5px 10px;
        border-radius: 3px;
        color: #fff !important;
        font-weight: 500
    }
    .des{
        font-weight: 600;
        font-size: 13px;
    }
    .study {
        background-color: #dbdbdb;
        padding: 5px 10px;
        border-radius: 2px;
        margin-top: -5px;
    }
    .tanggung{
        font-size: 12px;
        font-weight: 500;
    }
    .description{
        font-size: 12px;
    }
    .booking-card__info-price{
        background-color: #e6e6e6;
    }
</style>
@section('content')
        <div class="row header-container w-100">
            <div class="header-shadow">
                <div class="container">
                    <div class="detail-header d-flex">
                        <div class="logo-cv">
                            <img
                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                            src="{{ asset('storage/jobs'.'/'.$product->logo) }}" alt="cv-logo">
                        </div>
                        <div class="row">
                            <div class="container justify-content-center d-flex flex-column">
                                <div class="name-cv">
                                    <span class="company_name text-uppercase">
                                        {{ $product->company_name }}
                                    </span>
                                </div>
                                @php($prov = strtolower($product->province))
                                @php($city = strtolower($product->city))
                                @php($district = strtolower($product->district))
                                <div class="address-cv">
                                    {{ $product->note_address }}, {{ $district }}, {{ $city }}, {{ $prov }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container">
                <div class="row pb-4">
                    <div class="col-md-8">
                        <div class="job-title">
                            <h1 class="capitalize">
                                Lowongan kerja {{ $product->name }} at {{ $product->company_name }}
                            </h1>
                        </div>
                        <label class="status-employe text-uppercase">
                            {{ $product->status_employe }}
                        </label>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <span class="des">Deskripsi pekerjaan</span>
                            </div>
                            <div class="col-md-8">
                                <label class="study">
                                    Min. {{ $product->pendidikan }}
                                </label>
                                <div class="description">
                                    {!! $product->description !!}
                                </div>
                            </div>
                        </div>
                        <div class="share-btn">

                        </div>
                        <hr>
                    </div>
                    <div class="col-md-4">
                        <div class="booking-card --sticky mt-3">
                            <form id="add-to-cart-form">
                                @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <div class="booking-card__info-price w-100 text-center p-2">
                                <h5 class="booking-card__info-price-amount w-100">{{\App\CPU\Helpers::currency_converter($product->gaji)}}/{{ $product->satuan_gaji }}
                                </h5>
                            </span>
                            </div>
                            <section class="booking-card__info">

                                <div class="card-body" style="border: 1px solid #d8d8d8; border-radius: 3px;">
                                    <div class="row justify-content-between no-gutters mt-2" id="chosen_price_div">
                                        <div class="{{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">
                                            <div class="product-description-label">{{\App\CPU\translate('Tanggal_tayang')}}</div>
                                        </div>
                                        <div>
                                            <div class="product-price for-total-price">
                                                {{ Carbon\Carbon::parse($product->created_at)->format('d, M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-between no-gutters mt-2" id="chosen_price_div">
                                        <div class="{{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">
                                            <div class="product-description-label">{{\App\CPU\translate('Lamaran_ditutup')}}</div>
                                        </div>
                                        <div>
                                            <div class="product-price for-total-price">
                                                {{ Carbon\Carbon::parse($product->expire)->format('d, M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="pb-2 mt-2">
                                    <label class="penanggung d-block">Penanggung Jawab :</label>
                                    <span class="capitalize">
                                        {{ $product->penanggung_jwb }} (<strong>{{ $product->hp_penanggung_jwb }}</strong>)
                                    </span>
                                    <input type="hidden" id="auth" value="{{ auth('customer')->id() }}">

                                </div>
                                <div class="sewa mt-3">
                                    <button class="btn btn-outline-success text-uppercase w-100" onclick="check()" type="button">
                                        Lamar sekarang
                                    </button>
                                </div>

                            </section>
                            </form>
                        </div>
                        <!-- end booking card-->
                    </div>
                </div>
            </div>
            @if (auth('customer')->id())
            @include('web-views.jobs.apply')
            @endif
        </div>
@endsection
@push('script')
    <script>
        function check(){
            var user = $('#auth').val();
            if(!user){
                location.href = "{{route('customer.auth.login')}}";
            }else{
                $('#apply').modal('show')
            }

        }
    </script>
@endpush
