<!-- ========================================= RECOMMENDED BOOKS ========================================= -->
@if (isset($recommendedBooks) && count($recommendedBooks))
    <div class="container recommended-books section-slider">
        <div class="tab-holder line-top">
            <section class="section-home-slider d-flex flex-column">
                <div class="wrapper-title d-flex justify-content-between align-items-center">
                    <div class="title">
                        Users also bought
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="init-slider">
                            @foreach ($recommendedBooks as $product)
                                <div class="product-grid-holder d-flex justify-content-center">
                                    @component('site.components.product-grid-item', ['product' => $product, 'section' =>
                                        count($recommendedBooks) == 1 ? 'Recommended_1' : 'Recommended_'.count($recommendedBooks)])
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
<!-- ========================================= RECOMMENDED BOOKS : END ========================================= -->
