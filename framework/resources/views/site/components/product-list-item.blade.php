@if (isset($product))
  <div class="product-item product-item-holder">
    @if ($product->hasDiscount())
      <div class="ribbon red"><span>sale</span></div>
    @endif

    <div class="row">
      <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 image-holder">
        <div class="image">
          <a href="{{ route('site.products.show', $product->url) }}?section={{ $section }}">
            <img alt="{{ $product->title }}" src="{{ $product->imageUrl }}"/>
          </a>
        </div>
      </div><!-- /.image-holder -->
      <div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-8 body-holder">
        <div class="body">
          <div class="wrapper-info-product d-flex">
            <div class="image">
              <a href="{{ route('site.products.show', $product->url) }}?section={{ $section }}">
                <img alt="{{ $product->title }}" src="{{ $product->imageUrl }}"/>
              </a>
            </div>
            <div class="info-product">
              <div class="wrap-title m-b-15">
                <a class="primary-title" href="{{ route('site.products.show', $product->url) }}?section={{ $section }}">{{ $product->title }}</a>
                @component('site.components.product-rating', ['rating' => $product->rating])
                @endcomponent
              </div>
              @if(!$product->isBundle)
              <div class="brand"><strong>Publisher:</strong> {{ $product->publisher->name }}</div>
              <div class="brand"><strong>Author:</strong> {{ $product->author }}</div>
              <div class="brand">
                <strong>{{ $product->isComingSoon ? 'Expected release date' : 'Release date' }}:</strong> {{ $product->getPublishDateFormatted() }}
              </div>
              @endif

              @if ($product->hasDiscount())
                <div class="brand price-prev">${{ toFloatAmount($product->price) }}</div>
              @endif
            </div>
          </div>
          <div class="right-clmn">
            <div class="d-flex justify-content-start">
              <div class="price-current">${{ toFloatAmount($product->sellPrice) }}</div>
              <button-add-to-wishlist product="{{ $product->id }}"></button-add-to-wishlist>
            </div>

            @if (!$product->denyBuy())
              <div class="hover-area d-flex justify-content-start m-t-5">
                <div class="add-cart-button">
                  <button-add-to-cart
                    product="{{ $product->id }}"
                    deny-buy="{{ $product->denyBuy() }}"
                    bought="{{ product_bought($product->id) }}"
                    section="{{ $section }}"
                  />
                </div>
                @if(!$product->isBundle)
                <div class="sample-container m-l-10">
                  <a href="{{ route('site.products.sample', $product->url) }}">
                    View sample

                    <svg class="m-l-10" width="12" height="12" viewBox="0 0 12 12" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                      <path d="M0 5.24267V6.75782H9.09091L4.92424 10.9245L6 12.0002L12 6.00024L6 0.000244141L4.92424 1.076L9.09091 5.24267H0Z"
                            fill="#F96F34"/>
                    </svg>
                  </a>
                </div>
                @endif
              </div>
            @else
              <p class="text-success" style="font-size: 13px;font-weight: 600;">Not yet available</p>
            @endif
          </div>

          <div class="excerpt">
            <p class="description p-r-15">{!! html_entity_decode($product->description) !!}</p>
          </div>

        </div>
      </div><!-- /.body-holder -->

    </div><!-- /.row -->
  </div><!-- /.product-item -->
@endif