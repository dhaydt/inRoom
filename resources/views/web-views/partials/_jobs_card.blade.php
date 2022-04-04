{{-- @php($overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews)) --}}
<style>
.ell{
    /* width: 500px !important; */
}
.ell p {
    text-overflow:ellipsis;
    overflow:hidden;
    display: -webkit-box !important;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    white-space: normal;
    font-size: 12px;
}
.search-card{
    display: flex;
    flex-direction: column;
    height: 200px;
    background-color: transparent;
}
.card-loker{
    margin-bottom: 10px;
    box-shadow: none;
    border-radius: 7px;
    overflow: hidden;
}
.img-box-search{
    background: #999;
    border-radius: 8px;
    display: flex;
    height: 100%;
    overflow: hidden;
    position: relative;
    width: 60px;
    height: 60px;
}
.img-box-search img{
    height: 100%;
    width: 100%;
}
.product-card{
    position: relative;
    background-color: #fff;
}
.product-card .card-body.inline_product_search{
    background-color: #fff;
}
.rc-price__additional-data{
    justify-content: start;
}
.label-kost {
    position: absolute;
    left: 78%;
    top: 15px;
    padding: 3px 10px;
    border-radius: 5px;
    font-weight: 400;
    width: 82px;
    font-size: 13px;
    text-align: center;
    z-index: 5;
}
.job-name{
    font-size: 15px;
    font-weight: 400;
    color: #23c005;
}
.deadline{
    font-size: 12px;
    font-weight: 600;
}
.rc-info__location{
    color: #848484;
    font-size: 12px;
    font-weight: 500;
}
.gaji{
    font-size: 13px;
    font-weight: 500;
    color: #23c005;
}
@media (max-width: 500px){
    .img-box-search {
        height: 48px !important;
        width: 70px !important;
    }
    .card-body.inline_product_search {
        width: 100%;
    }
}
@media(max-width: 600px){
    .ell p {
        -webkit-line-clamp: 2;
        font-size: 10px;
    }
    .rc-price__additional-data{
        justify-content: start;
    }
}
</style>
<div class="product-card card-loker search-card card {{$product['current_stock']==0?'stock-card':''}}">
    <label class="label-kost text-white text-uppercase" style="background-color: {{ $web_config['primary_color'] }};">{{ $product->status_employe }}</label>

        <div class="card-header px-3 pt-3 inline_product clickable p-0 pb-1 d-flex flex-row" style="cursor: pointer;">
            <div class="d-flex align-items-center justify-content-center d-block img-box-search">
                <a href="{{route('job',$product->slug)}}" class="h-100 w-100">
                    <img
                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                        src="{{ asset('storage/jobs').'/'.$p['logo'] }}"
                        >
                </a>
            </div>
            <div class="job-name ml-2 capitalize text-left">
                {{ $product->name }}
            </div>
        </div>

        <div class="card-body inline_product_search text-left px-3 pb-3 p-0 clickable"
        style="cursor: pointer;">
        <div class="kost-rc__info d-flex flex-column justify-content-between h-100">
            <a href="{{route('job',$product->slug)}}">
                <div class="rc-info">
                    @php($city = strtolower($product->city))
                    @php($district = strtolower($product->district))
                    <span class="rc-info__name bg-c-text bg-c-text--body-4 capitalize">
                        {{ $product->company_name }}
                    </span>
                </div>
            </a>
            <span class="rc-info__location capitalize">
                {{ $district }}, {{ $city }}
            </span>
            <span class="gaji">{{ \App\CPU\Helpers::currency_converter($product->gaji) }} / {{ $product->satuan_gaji }}</span>
            <div class="row d-flex justify-content-between">
                <span class="stock-label ml-1 text-danger bg-c-text--label-1">
                    {{\App\CPU\translate('Buka_sampai : ')}}
                </span>
                <span class="text-grey deadline">{{ Carbon\Carbon::parse($product->expire)->format('d M, Y') }}</span>
            </div>
        </div>

    </div>
</div>
