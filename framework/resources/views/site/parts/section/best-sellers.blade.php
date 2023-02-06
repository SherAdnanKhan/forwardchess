<!-- ========================================= BEST SELLERS ========================================= -->
@if (isset($bestSellers))
    <div class="container section-slider">
        <div class="tab-holder line-top">
            <section class="section-best-sellers section-home-slider d-flex flex-column">
                <div class="wrapper-title d-flex justify-content-between align-items-center">
                    <div class="title">
                        Best Sellers
                    </div>
                    <div class="view-all">
                        <a href="{{ productsPageUrl(null, null, null, ['sortBy' => 'bestSeller']) }}&section=Best Seller" class="d-flex align-items-center">
                            View all
                            <svg class="m-l-10" width="12" height="12" viewBox="0 0 12 12" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 5.24267V6.75782H9.09091L4.92424 10.9245L6 12.0002L12 6.00024L6 0.000244141L4.92424 1.076L9.09091 5.24267H0Z"
                                      fill="#F96F34"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="best-sellers-slider init-slider">
                            @foreach ($bestSellers as $product)
                                <div class="product-grid-holder d-flex justify-content-center">
                                    @component('site.components.product-grid-item', ['product' => $product, 'section' => 'Best Seller'])
                                    @endcomponent
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endif
<!-- ========================================= BEST SELLERS : END ========================================= -->
