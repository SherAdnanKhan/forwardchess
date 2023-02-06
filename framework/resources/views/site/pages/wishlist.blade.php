@extends('layout.app')

@section('content')
    <section id="category-grid" class="category-grid p-t-35 p-b-80">
        <div class="container">
            <div class="row">
                <!-- ========================================= SIDEBAR ========================================= -->
                <div class="col-lg-12 d-flex align-items-center justify-content-between col-category-grid-title">
                    <div class="category-grid-title w-100 d-flex align-items-center justify-content-between">
                        Your Wishlist
                    </div>
                </div>
                <!-- ========================================= SIDEBAR : END ========================================= -->


                <!-- ========================================= CONTENT ========================================= -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="tab-content">
                        @if (count($products))
                            <div id="list-view" class="products-grid fade tab-pane in active">
                                <div class="products-list">
                                    @foreach ($products as $product)
                                        @component('site.components.product-list-item', ['product' => $product, 'section' => 'Wish list'])
                                        @endcomponent
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <h2 class="text-center">There are no products in your wishlist!</h2>
                        @endif
                    </div>
                </div><!-- /.col -->
            </div>

            <!-- ========================================= CONTENT : END ========================================= -->
        </div><!-- /.container -->
    </section><!-- /#category-grid -->
@endsection
