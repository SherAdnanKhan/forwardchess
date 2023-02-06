<!-- ========================================= Dynamic Products ========================================= -->
@if (isset($books) && count($books))
    <div class="container section-slider">
        <div class="tab-holder line-top">
            <section class="section-best-sellers section-home-slider d-flex flex-column">
                <div class="wrapper-title d-flex justify-content-between align-items-center">
                    <div class="title">
                        {{ $title }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="best-sellers-slider init-slider">
                            @foreach ($books as $product)
                                <div class="product-grid-holder d-flex justify-content-center">
                                    @component('site.components.product-grid-item', ['product' => $product, 'section' => $title])
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
<!-- ========================================= Dynamic Products : END ========================================= -->
