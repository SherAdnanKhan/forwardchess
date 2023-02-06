@if (isset($product))
  <div class="product-item-grid">
    @if ($product->hasDiscount())
      <div class="ribbon red"><span>sale</span></div>
    @endif

    <div class="content">
      <div class="image-block m-b-20">
        <a class="image-link" href="{{ route('site.products.show', $product->url) }}?section={{ $section ?? '' }}">
          <img class="image" alt="{{ $product->title }}" src="{{ $product->imageUrl }}"/>
        </a>

        @if (!$product->denyBuy() && !$product->isBundle)
          <div class="sample-container">
            <a href="{{ route('site.products.sample', $product->url) }}">
              View sample

              <svg class="m-l-10" width="12" height="12" viewBox="0 0 12 12" fill="none"
                   xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M0 5.24267V6.75782H9.09091L4.92424 10.9245L6 12.0002L12 6.00024L6 0.000244141L4.92424 1.076L9.09091 5.24267H0Z"
                  fill="#F96F34"/>
              </svg>
            </a>
          </div>
        @endif
      </div>
      <div class="text-block">
        <div class="title-block">
          <a
            class="title-link"
            href="{{ route('site.products.show', $product->url) }}?section={{ $section ?? '' }}"
            title="{{ $product->title }}"
          >
            {{ $product->title }}
          </a>

          @component('site.components.product-rating', ['rating' => $product->rating])
          @endcomponent
        </div>
        @if(!$product->isBundle)
        <div class="description-block">
          <strong class="strong">Publisher:</strong> {{ $product->publisher->name }}
        </div>
        @endif

        @if ($product->hasDiscount())
          <div class="description-block">
            <div class="price-prev">${{ toFloatAmount($product->price) }}</div>
          </div>
        @endif
      </div>
    </div>

    <div class="purchase">
      <div>
        <div class="prices d-flex justify-content-start">
          <div class="price-current">${{ toFloatAmount($product->sellPrice) }}</div>
          <button-add-to-wishlist product="{{ $product->id }}" class="m-l-10"/>
        </div>

        <div class="wrapper-button">
          @if (!$product->denyBuy())
            <div class="hover-area d-flex justify-content-start m-t-5">
              <div class="add-cart-button">
                <button-add-to-cart
                  product="{{ $product->id }}"
                  deny-buy="{{ $product->denyBuy() }}"
                  bought="{{ product_bought($product->id) }}"
                  section="{{ $section ?? '' }}"
                />
              </div>
            </div>
          @else
            <p class="text-success" style="font-size: 13px;font-weight: 600;">Not yet available</p>
          @endif
        </div>
      </div>
    </div>
  </div>
@endif
