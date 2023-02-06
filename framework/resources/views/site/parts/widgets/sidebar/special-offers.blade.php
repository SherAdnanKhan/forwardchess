@if (isset($offers) && ($offers->count()))
    <div class="widget special-offers m-t-20">
        <div class="wrap-filters">
            <div class="category-filter">
                <div class="wrapper-clear d-flex justify-content-between align-items-center">
                    <div class="filters-text">
                        special offers
                    </div>
                </div>
            </div>

            @foreach ($offers as $product)
                <div class="row product-item m-b-20">
                    <div class="col-xs-4 col-sm-4 no-margin">
                        <a href="{{ route('site.products.show', $product->url) }}">
                            <img alt="{{ $product->title }}" src="{{ $product->imageUrl }}" class="image"/>
                        </a>
                    </div>

                    <div class="col-xs-8 col-sm-8">
                        <a href="{{ route('site.products.show', $product->url) }}" title="{{ $product->title }}">{{ $product->title }}</a>
                        <div class="price">
                            <div class="price-prev">${{ toFloatAmount($product->price) }}</div>
                            <div class="price-current">${{ toFloatAmount($product->sellPrice) }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
