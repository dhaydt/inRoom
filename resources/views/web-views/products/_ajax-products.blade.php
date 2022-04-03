<style>
    .search-col{
        border-bottom: 1px solid #c8c8c8;
    }
</style>
@foreach($products as $product)
    @if(!empty($product['product_id']))
        @php($product=$product->product)
    @endif
    <div class="search-col {{Request::is('products*')?'col-lg-12 col-md-12 col-sm-12 col-12':'col-lg-2 col-md-3 col-sm-4 col-6'}} {{Request::is('shopView*')?'col-lg-3 col-md-4 col-sm-4 col-6':''}} pb-2 mb-3">
        @if(!empty($product))
            @include('web-views.partials._landscape_product',['p'=>$product])
        @endif
    </div>
@endforeach

<div class="col-12">
    <nav class="d-flex justify-content-between pt-2" aria-label="Page navigation"
         id="paginator-ajax">
        {!! $products->links() !!}
    </nav>
</div>
