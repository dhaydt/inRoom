<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        <i class="tio-align-to-top"></i> {{\App\CPU\translate('top_rented_rooms')}}
    </h5>
    <i class="tio-gift" style="font-size: 45px"></i>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <div class="row">
        @foreach($top_sell as $key=>$item)
        @php($i = $item['product'])
        @if(isset($i))
                <div class="col-md-4 col-6 mt-2"
                     onclick="location.href='{{route('admin.product.view',[$i['id']])}}'"
                     style="cursor: pointer;padding-right: 6px;padding-left: 6px">
                    <div class="grid-card">
                        <div class="label_1 row-center">
                            <div class="px-1">{{\App\CPU\translate('Rent')}} : </div>
                            <div>{{$item['count']}}</div>
                        </div>
                        <div class="text-center mt-3">
                            <img style="height: 90px"
                                 src="{{\App\CPU\ProductManager::product_image_path('product')}}/{{json_decode($i['images'])[0]}}"
                                 onerror="this.src='{{asset('public/assets/back-end/img/160x160/img2.jpg')}}'"
                                 alt="product image">
                        </div>
                        <div class="text-center mt-2">
                            <span class="" style="font-size: 10px">{{substr($i['name'],0,20)}} {{strlen($i['name'])>20?'...':''}}</span>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
<!-- End Body -->
