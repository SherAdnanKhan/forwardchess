@extends('layout.app')

@section('content')
  @component('site.components.breadcrumb', ['publishers' => $publishers])
    <div class="row">
      <div class="col-lg-12">
        <ul class="breadcrumb-block d-flex p-t-105 p-b-30">
          <li class="breadcrumb-item d-flex align-items-center m-r-10">
            <a class="description" href="{{ productsPageUrl() }}">Browse E-Books</a>
            <svg class="m-l-10 m-b-2" width="8" height="11" viewBox="0 0 8 11" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
              <path
                d="M1.30849 9.31432L5.43302 5.49508L1.30849 1.67584C0.893914 1.29195 0.893914 0.671812 1.30849 0.287919C1.72307 -0.0959732 2.39277 -0.0959732 2.80735 0.287919L7.68662 4.80604C8.1012 5.18993 8.1012 5.81007 7.68662 6.19396L2.80735 10.7121C2.39277 11.096 1.72307 11.096 1.30849 10.7121C0.904544 10.3282 0.893914 9.69821 1.30849 9.31432Z"
                fill="#757575"/>
            </svg>
          </li>
          <li class="breadcrumb-item d-flex align-items-center m-r-10">
            <a class="description" href="{{ productsPageUrl(null, $product->publisher->name) }}">{{ $product->publisher->name }}</a>
            <svg class="m-l-10 m-b-2" width="8" height="11" viewBox="0 0 8 11" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
              <path
                d="M1.30849 9.31432L5.43302 5.49508L1.30849 1.67584C0.893914 1.29195 0.893914 0.671812 1.30849 0.287919C1.72307 -0.0959732 2.39277 -0.0959732 2.80735 0.287919L7.68662 4.80604C8.1012 5.18993 8.1012 5.81007 7.68662 6.19396L2.80735 10.7121C2.39277 11.096 1.72307 11.096 1.30849 10.7121C0.904544 10.3282 0.893914 9.69821 1.30849 9.31432Z"
                fill="#757575"/>
            </svg>
          </li>
          <li class="breadcrumb-item d-flex align-items-center">
            <a class="secondary-description">{{ $product->title }}</a>
          </li>
        </ul>
        <div class="breadcrumb-link">
          <a class="back-link d-flex align-items-center m-t-45 m-b-30" href="{{ route('site.products.index') }}">
            <svg class="m-r-10 m-b-2" width="12" height="12" viewBox="0 0 12 12" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
              <path d="M12 5.24267V6.75782H2.90909L7.07576 10.9245L6 12.0002L0 6.00024L6 0.000244141L7.07576 1.076L2.90909 5.24267H12Z"
                    fill="#F96F34"/>
            </svg>
            Back
          </a>
        </div>
      </div>
    </div>
  @endcomponent

  <section id="single-product" class="section-single-product">
    <div class="container">
      <div class="single-product-block block-lines">
        <div class="row no-margin">
          <div class="col-sm-3 col-md-3 col-lg-3 no-padding">
            <div class="image-block d-flex justify-content-center align-items-center">
              <img class="image" alt="{{ $product->title }}" src="{{ $product->imageUrl }}"/>
            </div><!-- /.single-product-gallery -->
            <div class="purchase d-flex align-items-center justify-content-between m-t-25">
              <div class="price-current m-l-25">${{ toFloatAmount($product->sellPrice) }}</div>

              @if ($product->hasDiscount())
                <div class="price-prev m-l-5 m-r-5">${{ toFloatAmount($product->price) }}</div>
              @endif

              <button-add-to-cart
                class="le-button"
                product="{{ $product->id }}"
                deny-buy="{{ $product->denyBuy() }}"
                bought="{{ product_bought($product->id) }}"
                section="{{ $section }}"
              />
            </div>
          </div>
          <div class="col-sm-9 col-md-9 col-lg-9 no-padding">

            <div class="wishlist-btn-wrapper">
              <div class="col-lg-12">
                <div class="title m-r-40">{{ $product->title }}</div>

                @if (!$product->denyBuy() && !$product->isBundle)
                  <div class="row m-t-5">
                    <div class="col-lg-12 d-flex justify-content-between">
                      <div class="sample-container">
                        <a href="{{ route('site.products.sample', $product->url) }}">
                          View sample

                          <svg class="m-l-10" width="12" height="12" viewBox="0 0 12 12" fill="none"
                               xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 5.24267V6.75782H9.09091L4.92424 10.9245L6 12.0002L12 6.00024L6 0.000244141L4.92424 1.076L9.09091 5.24267H0Z"
                                  fill="#F96F34"/>
                          </svg>
                        </a>
                      </div>
                      <button-add-to-wishlist product="{{ $product->id }}"/>
                    </div>
                  </div>
                @endif

                <div class="wrapper-content d-flex">
                  <div class="first-block m-r-30">
                    @if(!$product->isBundle)
                      <div class="secondary-description">
                        <strong class="strong">Publisher:</strong>
                        <a href="{{ productsPageUrl(null, $product->publisher->name) }}">{{ $product->publisher->name }}</a>
                      </div>
                    @else
                      <div class="secondary-description">
                        <strong class="strong">This product is a bundle</strong>
                      </div>
                    @endif

                    @if (!empty($product->categories))
                      <div class="secondary-description"><strong
                          class="strong">Categories:</strong>
                        @foreach ($product->categories as $index => $category)
                          <a class="category" href="{{ productsPageUrl($category->url) }}">{{ $category->name }}</a>
                          @if (!$loop->last)
                            &nbsp;|&nbsp;
                          @endif
                        @endforeach
                      </div>
                    @endif

                    @if (!$product->isBundle)
                      <div class="secondary-description">
                        <strong class="strong">Author:</strong> {{ $product->author }}
                      </div>
                      <div class="secondary-description">
                        <strong class="strong">Level:</strong> {{ ucfirst($product->level) }}
                      </div>
                    @endif
                  </div>

                  @if (!$product->isBundle)
                    <div class="second-block">
                      <div class="secondary-description">
                        <strong class="strong">Contents:</strong> {{ $product->nrPages }} pages
                      </div>
                      <div class="secondary-description"><strong class="strong">Product type:</strong>
                        Ebook
                      </div>
                      <div class="secondary-description">
                        <strong class="strong">
                          {{ $product->isComingSoon ? 'Expected release date' : 'Release date' }}:
                        </strong>
                        {{ $product->getPublishDateFormatted() }}
                      </div>
                    </div>
                  @endif

                  <div class="purchase-hide d-flex align-items-center justify-content-between m-t-25">
                    <div class="price-current m-l-25">
                      ${{ toFloatAmount($product->sellPrice) }}
                    </div>

                    @if ($product->hasDiscount())
                      <div class="price-prev">${{ toFloatAmount($product->price) }}</div>
                    @endif

                    <button-add-to-cart
                      class="le-button m-r-20"
                      product="{{ $product->id }}"
                      deny-buy="{{ $product->denyBuy() }}"
                      bought="{{ product_bought($product->id) }}"
                    />
                  </div>
                </div>
              </div>
            </div>

            <div class="row m-l-5">
              <div class="col-lg-12">
                <div class="excerpt m-t-25 m-r-20 m-b-20">
                  <p class="description">{!! html_entity_decode($product->description) !!}</p>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="excerpt m-t-25 m-r-20 m-b-20">
                  @if (count($children) > 0)
                    <div class="row">
                      <div class="wrapper-title d-flex justify-content-between align-items-center">
                        <div class="titles">
                          <strong>Books in this Bundle</strong>
                        </div>
                      </div>
                      <section class="sliders m-t-40">
                        <div class="sliders-init">
                          @foreach ($children as $children)
                            <div class="slide d-flex justify-content-center">
                              <div class="wrapper">
                                <div class="image m-b-15">
                                  <a href="{{ route('site.products.show', $children->url) }}">
                                    <img alt="{{ $children->title }}" src="{{ $children->imageUrl }}"/>
                                  </a>
                                </div>
                                <div class="content m-l-20 m-r-15">
                                  <div class="description prod-description m-b-5">
                                    <a class="small-title" href="{{ route('site.products.show', $children->url) }}">{{ $children->title }}</a>
                                  </div>
                                  <div class="description-small-dark m-b-8">
                                    <span>Publisher:</span> {{ $children->publisher->name }}
                                  </div>
                                  <div class="price-current">
                                    ${{ toFloatAmount($children->sellPrice) }}
                                  </div>
                                </div>
                              </div>
                            </div>
                          @endforeach
                        </div>
                      </section>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
        @if (!$product->isBundle)
          <review-list
            nickname="{{ $review['nickname'] }}"
            product-id="{{ $product->id }}"
            rating="{{ $product->rating }}"
            can-add="{{ $review['isAllowed'] }}"
          />
        @endif
      </div>
    </div><!-- /.single-product -->
  </section>

  <section class="sliders m-t-80">
    <div class="container">
      @if ($sameCategory)
        <div class="row">
          <div class="wrapper-title d-flex justify-content-between align-items-center">
            <div class="title">
              You may also like
            </div>
            <div class="wrapper-block">

            </div>
          </div>

          <div class="sliders-init m-t-40">
            @foreach ($sameCategory as $relatedProd)
              <div class="slide d-flex justify-content-center">
                <div class="wrapper">
                  <div class="image m-b-15">
                    <a href="{{ route('site.products.show', $relatedProd->url) }}">
                      <img alt="{{ $relatedProd->title }}" src="{{ $relatedProd->imageUrl }}"/>
                    </a>
                  </div>
                  <div class="content m-l-20 m-r-15">
                    <div class="description prod-description m-b-5">
                      <a class="small-title" href="{{ route('site.products.show', $relatedProd->url) }}">{{ $relatedProd->title }}</a>
                    </div>
                    <div class="description-small-dark m-b-8">
                      <span>Publisher:</span> {{ $relatedProd->publisher->name }}
                    </div>
                    <div class="price-current">
                      ${{ toFloatAmount($relatedProd->sellPrice) }}
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      @if ($samePublisher)
        <div class="row p-t-70 line-top">
          <div class="wrapper-title d-flex justify-content-between align-items-center">
            <div class="title">
              More by {{ $product->publisher->name }}
            </div>
            <div class="wrapper-block">

            </div>
          </div>

          <div class="sliders-init m-t-40">
            @foreach ($samePublisher as $relatedProd)
              <div class="slide d-flex justify-content-center">
                <div class="wrapper">
                  <div class="image m-b-15">
                    <a href="{{ route('site.products.show', $relatedProd->url) }}">
                      <img alt="{{ $relatedProd->title }}" src="{{ $relatedProd->imageUrl }}"/>
                    </a>
                  </div>
                  <div class="content m-l-20 m-r-15">
                    <div class="description prod-description m-b-5">
                      <a class="small-title" href="{{ route('site.products.show', $relatedProd->url) }}">{{ $relatedProd->title }}</a>
                    </div>
                    <div class="description-small-dark m-b-8">
                      <span>Publisher:</span> {{ $relatedProd->publisher->name }}
                    </div>
                    <div class="price-current">
                      ${{ toFloatAmount($relatedProd->sellPrice) }}
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </section>
@endsection
