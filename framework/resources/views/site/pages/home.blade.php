{{--<div id="home-page" class="home-page d-none"></div>--}}
@extends('layout.app')

@section('content')
    <div id="top-banner-and-menu">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 homebanner-holder">
                    @include('site.parts.section.home-page-apps')
                </div><!-- /.homebanner-holder -->
            </div>
        </div><!-- /.container -->
        <div class="mouse">
            <img src="{{ asset('images/background-images/mouse-bg/Mouse.png') }}" alt="">
        </div>
    </div><!-- /#top-banner-and-menu -->

{{-- ============================================================================================================ --}}


{{-- ============================================================================================================ --}}
    @include('site.parts.section.products-reuseable-component', ['books' => $bestOfYear, 'title' => 'Best of 2021'])
    @include('site.parts.section.home-page-tabs')
    @include('site.parts.section.best-sellers')
    @include('site.parts.section.products-reuseable-component', ['books' => $mostWished, 'title' => 'Most Wished'])

    @if (isset($productsTabs['comingSoon']) && !$productsTabs['comingSoon']->isEmpty())
        <div class="container section-slider">
            <div class="tab-holder line-top">
                <section class="section-coming-soon section-home-slider d-flex flex-column">
                    <div class="wrapper-title d-flex justify-content-between align-items-center">
                        <div class="title">
                            Coming Soon
                        </div>
                        <div class="view-all">
                            <a href="{{ productsPageUrl('coming-soon') }}"
                               class="d-flex align-items-center">
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
                            <div class="coming-soon-slider init-slider">
                                @foreach ($productsTabs['comingSoon'] as $product)
                                    <div class="product-grid-holder d-flex justify-content-center">
                                        @component('site.components.product-grid-item', ['product' => $product])
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

    @include('site.parts.section.top-brands')

    <section class="section-gift-card-home">
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-md-6 col-lg-6">
                    <div class="image">
                        <img src="{{ asset('images/home-gift-card.png') }}" alt="">
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="content">
                        <div class="title m-b-15">
                            Not sure what to give as a present?
                        </div>
                        <div class="primary-description m-b-30">
                            You can choose a gift card of any value to please a friend or family member.
                        </div>
                        <div class="buttons-holder">
                            <a href="{{ route('site.gift-card.index') }}"
                               class="le-button d-flex justify-content-center align-items-center">
                                <svg class="m-r-15" width="19" height="19" viewBox="0 0 19 19" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.7 15.2C4.655 15.2 3.8095 16.055 3.8095 17.1C3.8095 18.145 4.655 19 5.7 19C6.745 19 7.6 18.145 7.6 17.1C7.6 16.055 6.745 15.2 5.7 15.2ZM0 0V1.9H1.9L5.32 9.1105L4.0375 11.438C3.8855 11.704 3.8 12.0175 3.8 12.35C3.8 13.395 4.655 14.25 5.7 14.25H17.1V12.35H6.099C5.966 12.35 5.8615 12.2455 5.8615 12.1125L5.89 11.9985L6.745 10.45H13.8225C14.535 10.45 15.162 10.0605 15.485 9.4715L18.886 3.306C18.962 3.173 19 3.0115 19 2.85C19 2.3275 18.5725 1.9 18.05 1.9H3.9995L3.1065 0H0ZM15.2 15.2C14.155 15.2 13.3095 16.055 13.3095 17.1C13.3095 18.145 14.155 19 15.2 19C16.245 19 17.1 18.145 17.1 17.1C17.1 16.055 16.245 15.2 15.2 15.2Z"
                                          fill="white"/>
                                </svg>
                                Buy Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-subscribe sub-form-row ">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-12">
                    <subscribe></subscribe>
                </div>
            </div>
        </div>
    </section>
@endsection

