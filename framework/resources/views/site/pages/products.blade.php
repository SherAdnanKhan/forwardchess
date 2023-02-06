@extends('layout.app')

@section('content')
    <form id="products-form" action="{{ $filters['formAction'] }}">
        <section id="category-grid" class="category-grid p-t-35 p-b-80">
            <div class="container">
                <div class="row">
                    <!-- ========================================= SIDEBAR ========================================= -->
                    <div class="col-lg-12 d-flex align-items-center justify-content-between col-category-grid-title">
                        <div class="category-grid-title w-100 d-flex align-items-center justify-content-between">
                            Browse E-books
                            <button type="button"
                                    class="mobile-filters"
                                    onclick="$('.product-filter').toggle()"
                            >
                                <svg class="d-flex align-items-center justify-content-center w-100" width="20"
                                     height="15" viewBox="0 0 20 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.09998 13.7288C7.09998 13.078 7.60507 12.5576 8.21949 12.5576H12C12.6144 12.5576 13.1195 13.078 13.1195 13.7288C13.1195 14.3796 12.6144 14.9 12 14.9H8.21949C7.60507 14.9 7.09998 14.3796 7.09998 13.7288Z"
                                          fill="#F96F34"/>
                                    <path d="M4.09998 7.37289C4.09998 6.72211 4.60507 6.2017 5.21949 6.2017H14.7805C15.3949 6.2017 15.9 6.72211 15.9 7.37289C15.9 8.02367 15.3949 8.54407 14.7805 8.54407H5.21949C4.60507 8.54407 4.09998 8.02367 4.09998 7.37289Z"
                                          fill="#F96F34"/>
                                    <path d="M0.0999756 1.27119C0.0999756 0.620412 0.605069 0.100006 1.21949 0.100006H18.7805C19.3949 0.100006 19.9 0.620412 19.9 1.27119C19.9 1.92197 19.3949 2.44238 18.7805 2.44238H1.21949C0.605069 2.44238 0.0999756 1.92197 0.0999756 1.27119Z"
                                          fill="#F96F34"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-lg-3 col-widget">
                        @include('site.parts.widgets.sidebar.product-filter')
                        @include('site.parts.widgets.sidebar.special-offers')
                    </div>
                    <!-- ========================================= SIDEBAR : END ========================================= -->

                    <!-- ========================================= CONTENT ========================================= -->
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 sidebar col-sidebar">
                        {{--@include('site.parts.section.recommended-products')--}}
                        @include('site.parts.section.products-listing')
                    </div><!-- /.col -->
                </div>

                <!-- ========================================= CONTENT : END ========================================= -->
            </div><!-- /.container -->
        </section><!-- /#category-grid -->
    </form>
@endsection
