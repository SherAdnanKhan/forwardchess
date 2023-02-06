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
                <div class="col-md-7 center-block">
                    <div class="info text-center">
                        <h2 class="primary-color inner-bottom-xs" style="font-size: 20px;">There was a problem with your payment!</h2>

                        <p class="lead">Click <a href="{{ $paypalUrl }}">here</a> to try again.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
