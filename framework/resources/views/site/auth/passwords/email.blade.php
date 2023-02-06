@extends('layout.app')

@section('content')
    <section class="section-reset-passowrd">
        <div class="container">
            <div class="row wrapper">
                <a class="back" href="{{ route('login') }}">
                    <svg class="m-r-5" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5.24267V6.75782H2.90909L7.07576 10.9245L6 12.0002L0 6.00024L6 0.000244141L7.07576 1.076L2.90909 5.24267H12Z" fill="#F96F34"/>
                    </svg>
                    Back
                </a>
            </div>
            <div class="row reset-password">
                @if (!empty(session('status')))
                    <div class="description">
                        {{session('status')}}
                    </div>
                @else
                    <div class="primary-title m-b-15">
                        {{ __('Forgot Password?') }}
                    </div>

                    <div class="description m-b-25">
                        Please enter your email address and we will send you a link to change your password
                    </div>
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="field-row">
                            <label class="m-b-10" for="email">{{ __('Your Email') }}</label>
                            <input type="email" id="email" name="email" class="m-b-25 le-input{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}"
                                   placeholder="Enter Your Email"
                                   required>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="buttons-holder">
                            <button type="submit" class="le-button w-100"> {{ __('Send Me a Link') }}</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </section>
@endsection
