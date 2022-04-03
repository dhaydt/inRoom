<style>
    .search-col{
        border-bottom: 1px solid #c8c8c8;
    }
</style>
@foreach($products as $product)
    @if(!empty($product['product_id']))
        @php($product=$product->product)
    @endif
    <div class="search-col col-lg-6 col-md-6 col-sm-6 col-12 pb-2 mb-3">
        @if(!empty($product))
            @include('web-views.partials._jobs_card',['p'=>$product])
        @endif
    </div>
@endforeach

<div class="col-12">
    <nav class="d-flex justify-content-between pt-2" aria-label="Page navigation"
         id="paginator-ajax">
        {!! $products->links() !!}
    </nav>
</div>
