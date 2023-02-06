@extends('layout.app')

@section('content')
    <section class="section-authentication">
        <div class="container">
            <div class="row verify">
                <div class="primary-title m-b-15 m-r-20 m-l-20">
                    Confirm Your Email
                </div>

                @if (session('resent'))
                    <div class="alert alert-success m-10" role="alert">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif

                <div class="description descr m-r-20 m-l-20">
                    We emailed a confirmation link to <a class="color-link" href="#">{{Auth::user()->email}}</a>
                </div>
                <div class="description m-b-25 m-r-20 m-l-20">
                    Check your email for link to proceed to checkout
                </div>
                <div class="line"></div>
                <div class="secondary-description m-b-25 m-t-25 m-r-20 m-l-20">
                    Didn’t get confirmation Email? Make sure you’ve entered the right address or check your Spam folder
                </div>
                <div class="flex-group d-flex align-items-center justify-content-between m-r-20 m-l-20">
                    <div class="order-1">
                        Didn’t got the link?
                        <a class="color-link" href="{{ route('verification.resend') }}" onclick="event.preventDefault(); document.getElementById('resend-form').submit();">Send it again</a>
                    </div>

                    <form id="resend-form" action="{{ route('verification.resend') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
