@extends('layout.app')

@section('content')

    <section id="authentication" class="section-authentication">
        <div class="container">
            <div class="row authentication sign-in">
                <div class="primary-title m-b-15">
                    Sign In
                </div>
                <div class="description m-b-25">
                    Welcome back, please enter your account
                </div>
                <form role="form" class="" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="m-b-20">
                        <label class="m-b-10">{{ __('Your Email') }}</label>
                        <input type="email" id="email" name="email" class="le-input{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" required placeholder="Enter your email">
                    @if ($errors->has('email'))
                            <span>
                                <strong class="error">{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="wrapper-password m-b-20">
                        <label class="m-b-10">{{ __('Your Password') }}</label>
                        <input type="password" id="password" name="password" class="password le-input{{ $errors->has('password') ? ' is-invalid' : '' }}" required placeholder="Enter your password">
                        <svg class="show-password" width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 5.5C10 6.88 8.88 8 7.5 8C7.5 9.38 8.62 10.5 10 10.5C11.38 10.5 12.5 9.38 12.5 8C12.5 6.62 11.38 5.5 10 5.5ZM10 0.5C4.8725 0.5 0 6.31 0 8C0 9.69 4.8725 15.5 10 15.5C15.1275 15.5 20 9.69 20 8C20 6.31 15.1275 0.5 10 0.5ZM10 13C7.23875 13 5 10.7613 5 8C5 5.23875 7.23875 3 10 3C12.7613 3 15 5.23875 15 8C15 10.7613 12.7613 13 10 13Z"
                                  fill="#757575"/>
                        </svg>

                        @if ($errors->has('password'))
                            <span>
                                <strong class="error">{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between m-b-30">
                        <div class="wrapper-remember">
                            <input type="checkbox" name="remember" class="le-checkbox" {{ old('remember') ? 'checked' : '' }}>
                            <span class="fz-14">{{ __('Remember Me') }}</span>
                        </div>
                        <div>
                            <a class="fz-14" href="{{ route('password.request') }}" class="">{{ __('Forgot Password?') }}</a>
                        </div>
                    </div>
                    <div class="m-b-20">
                        <button type="submit" class="le-button w-100">{{ __('Sign In') }}</button>
                    </div><!-- /.buttons-holder -->
                </form>
                <div class="wrapper-icon-block d-flex justify-content-between align-items-center">
                    {{--<div class=" icon-blocks d-flex">
                        <div class="icon-block m-r-10 d-flex justify-content-center align-items-center">
                            <a class="d-flex align-items-center justify-content-center"
                               href="#">
                                <svg width="9" height="19" viewBox="0 0 9 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 0.5V4.1H7.2C6.579 4.1 6.3 4.829 6.3 5.45V7.7H9V11.3H6.3V18.5H2.7V11.3H0V7.7H2.7V4.1C2.7 3.14522 3.07928 2.22955 3.75442 1.55442C4.42955 0.879285 5.34522 0.5 6.3 0.5H9Z" fill="white"/>
                                </svg>
                            </a>
                        </div>
                        <div class="icon-block d-flex justify-content-center align-items-center">
                            <a class="d-flex align-items-center justify-content-center"
                               href="#">
                                <svg width="23" height="15" viewBox="0 0 23 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22.5 6.5H20.5V4.5H18.5V6.5H16.5V8.5H18.5V10.5H20.5V8.5H22.5V6.5ZM7.5 6.5V8.9H11.5C11.3 9.9 10.3 11.9 7.5 11.9C5.1 11.9 3.2 9.9 3.2 7.5C3.2 5.1 5.1 3.1 7.5 3.1C8.9 3.1 9.8 3.7 10.3 4.2L12.2 2.4C11 1.2 9.4 0.5 7.5 0.5C3.6 0.5 0.5 3.6 0.5 7.5C0.5 11.4 3.6 14.5 7.5 14.5C11.5 14.5 14.2 11.7 14.2 7.7C14.2 7.2 14.2 6.9 14.1 6.5H7.5Z" fill="white"/>
                                </svg>
                            </a>
                        </div>
                    </div>--}}
                    <div>
                        <div class="authentication-redirect">
                            <span class="description">Don’t have an account? </span><a href="{{ route('register') }}">Sign Up</a>
                        </div>
                        <div class="block-left-right-lines text-center">
                            <span>or</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section><!-- /.authentication -->
@endsection
