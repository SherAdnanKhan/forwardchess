@extends('layout.app')

@section('content')

    <main id="order-page" class="order-page m-t-65 m-b-60">
        <div class="container">
            <div class="row m-b-30">
                <div class="col-lg-12">
                    <a class="back"
                       href="{{ route('site.orders.index') }}">
                        <svg class="m-r-10" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5.24267V6.75782H2.90909L7.07576 10.9245L6 12.0002L0 6.00024L6 0.000244141L7.07576 1.076L2.90909 5.24267H12Z"
                                  fill="#F96F34"/>
                        </svg>
                        {{ __('Order\'s history') }}
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="order-details">
                        <div class="order-info">
                            <div class="title m-b-15">
                                Order Details
                            </div>
                            <div class="wrap d-flex m-b-15">
                                <div class="secondary-description width-description m-r-30">
                                    Order ID
                                </div>
                                <div class="description">
                                    {{ $order->refNo }}
                                </div>
                            </div>
                            <div class="wrap d-flex m-b-15">
                                <div class="secondary-description width-description m-r-30">
                                    Date
                                </div>
                                <div class="description">
                                    {{ $order->getCreatedAtFormatted('m/d/Y') }}
                                </div>
                            </div>
                            <div class="wrap d-flex m-b-15">
                                <div class="secondary-description width-description m-r-30">
                                    Status
                                </div>
                                <div class="description">
                                    {{ $order->status }}
                                </div>
                            </div>
                            <div class="wrap d-flex">
                                <div class="secondary-description width-description m-r-30">
                                    Total
                                </div>
                                <div class="description">
                                    ${{ $order->total }}
                                </div>
                            </div>
                        </div>
                        <div class="order-address">
                            <div class="profile-description m-b-15">
                                Customer details
                            </div>
                            <div class="d-flex">
                                <span class="description">
                                    {{ $order->billing->firstName }} {{ $order->billing->lastName }}<br>
                                    {{ $order->billing->countryName() }}
                                </span>
                            </div>
                        </div>
                        <div class="ordered-books">
                            <div class="primary-title">
                                Ordered items:
                            </div>
                            @foreach ($order->items as $item)
                                @if ($item->detail)
                                    @if ($item->isProduct())
                                        <div class="product d-flex">
                                            <div class="image m-r-20">
                                                <a href="{{ route('site.products.show', $item->detail->url) }}">
                                                    <img alt="{{ $item->detail->title }}" src="{{ $item->detail->imageUrl }}"/>
                                                </a>
                                            </div>
                                            <div class="content">
                                                <div class="secondary-title m-b-15">
                                                    <a class="primary-title" href="{{ route('site.products.show', $item->detail->url) }}">{{ $item->product->title }}</a>
                                                </div>
                                                <div class="description-text d-flex m-b-10">
                                                    Publisher: &nbsp; <span>{{ $item->detail->publisher->name }}</span>
                                                </div>
                                                <div class="description-text d-flex m-b-20">
                                                    Author:&nbsp; <span>{{ $item->detail->author }}</span>
                                                </div>
                                                <div class="price-current">
                                                    ${{ $item->total }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="product d-flex">
                                            <div class="content">
                                                <div class="secondary-title m-b-15">
                                                    {{ $item->detail->code }}
                                                </div>
                                                <div class="price-current">
                                                    ${{ $item->total }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="product d-flex">
                                        <div class="content">
                                            <div class="secondary-title m-b-15">
                                                {{ $item->name }}
                                            </div>
                                            <div class="price-current">
                                                ${{ $item->total }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection