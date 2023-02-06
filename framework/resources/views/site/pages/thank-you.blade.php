@extends('layout.app')

@section('content')
    @component('site.components.breadcrumb', ['publishers' => $publishers])
        <ul>
            <li class="breadcrumb-item current gray">
                <a>Order placed</a>
            </li>
        </ul>
    @endcomponent

    <main id="thank-you-page" class="inner">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="wrapper-thank-you-page">
                        <div class="info text-center">
                            <h2 class="title-order">Thank you for your order!</h2>

                            <div class="order-image">
                                <img src="{{ asset('images/order.png') }}" alt="">
                            </div>

                            <div class="description-order">
                                <table class="table info-table">
                                    <tr>
                                        <td>
                                            <label>Reference</label>
                                        </td>
                                        <td>
                                            <div class="value">{{ $order->refNo }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Total</label>
                                        </td>
                                        <td>
                                            <div class="value">${{ $order->total }}</div>
                                        </td>
                                    </tr>
                                </table>

                                <p class="lead">You can see more details <a href="{{ route('site.orders.display', $order->refNo) }}">here</a></p>

                                @if (!empty($pixel))
                                    {!! $pixel !!}
                                    <script defer async type="text/javascript" src="https://shareasale-analytics.com/j.js"></script>
                                @endif
                            </div>
                        </div>
                        <div class="wrapper-download-section text-center">
                            <div class="description-order download-descr">
                                Please download our <a href="{{ route('site.user-guide') }}">FREE App</a> to read your E-books. To
                                Sign In use
                                Email and Password you’ve indicated when you signed up on Forwardchess.com.
                            </div>
                            <div class="wrapper-download d-flex justify-content-center">
                                <div class="wrapper-download-mobile">
                                    <a href="https://itunes.apple.com/us/app/forwardchess/id543005909?mt=8" target="_blank">
                                        <img src="{{ asset('images/download-image/IOS.jpg') }}" alt="">
                                    </a>
                                    <a href="https://play.google.com/store/apps/details?id=com.forwardchess" target="_blank">
                                        <img src="{{ asset('images/download-image/Android.jpg') }}" alt="">
                                    </a>
                                </div>
                                <div class="wrapper-download-pc">
                                    <a class="d-flex justify-content-center align-items-center h-100" href="https://storage.googleapis.com/fchess-installers/windows/ForwardChess-Latest.exe"
                                       target="_blank">
                                        <img src="{{ asset('images/download-image/windows.svg') }}" alt="">
                                        Get it on Windows
                                    </a>
                                </div>
                                <div class="wrapper-download-pc">
                                    <a class="d-flex justify-content-center align-items-center h-100" href="https://storage.googleapis.com/fchess-installers/mac/ForwardChess-Latest.pkg"
                                       target="_blank">
                                        <img src="{{ asset('images/download-image/mac.svg') }}" alt="">
                                        Get it on MacOS
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    @component('site.components.referral')
    @endcomponent
@endsection
