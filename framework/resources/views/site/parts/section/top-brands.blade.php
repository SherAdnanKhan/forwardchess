<!-- ========================================= TOP BRANDS ========================================= -->
@if (isset($publishers))
    <section id="top-brands" class="section-publishers">
        <div class="container">
            <div class="tab-holder">
                <div class="title m-t-80 m-b-40">
                    Publishers
                </div>
                <div class="parent block-lines d-flex">
                    @foreach ($publishers as $publisher)
                        <div class="child d-flex justify-content-center align-items-center">
                            <a href="{{ productsPageUrl(null, $publisher->name) }}">
                                <img alt="{{ $publisher->name }}" src="{{ $publisher->logoUrl }}"/>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
<!-- ========================================= TOP BRANDS : END ========================================= -->


