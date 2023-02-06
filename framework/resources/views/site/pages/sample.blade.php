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
                            <path d="M1.30849 9.31432L5.43302 5.49508L1.30849 1.67584C0.893914 1.29195 0.893914 0.671812 1.30849 0.287919C1.72307 -0.0959732 2.39277 -0.0959732 2.80735 0.287919L7.68662 4.80604C8.1012 5.18993 8.1012 5.81007 7.68662 6.19396L2.80735 10.7121C2.39277 11.096 1.72307 11.096 1.30849 10.7121C0.904544 10.3282 0.893914 9.69821 1.30849 9.31432Z"
                                  fill="#757575"/>
                        </svg>
                    </li>
                    <li class="breadcrumb-item d-flex align-items-center m-r-10">
                        <a class="description" href="{{ productsPageUrl(null, $product->publisher->name) }}">{{ $product->publisher->name }}</a>
                        <svg class="m-l-10 m-b-2" width="8" height="11" viewBox="0 0 8 11" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.30849 9.31432L5.43302 5.49508L1.30849 1.67584C0.893914 1.29195 0.893914 0.671812 1.30849 0.287919C1.72307 -0.0959732 2.39277 -0.0959732 2.80735 0.287919L7.68662 4.80604C8.1012 5.18993 8.1012 5.81007 7.68662 6.19396L2.80735 10.7121C2.39277 11.096 1.72307 11.096 1.30849 10.7121C0.904544 10.3282 0.893914 9.69821 1.30849 9.31432Z"
                                  fill="#757575"/>
                        </svg>
                    </li>
                    <li class="breadcrumb-item d-flex align-items-center">
                        <a href="{{ route('site.products.show', $product->url) }}" class="secondary-description">{{ $product->title }}</a>
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
            <iframe class="sample-iframe" src="https://read.forwardchess.com/reader?id={{$product->sku}}"></iframe>
        </div><!-- /.single-product -->
    </section>
@endsection
