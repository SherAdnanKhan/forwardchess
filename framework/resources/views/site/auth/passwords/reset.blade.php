@extends('layout.app')

@section('content')
        <main id="register-page" class="inner-bottom-md">
        <div class="container">
            <div class="row">
                <section class="section">
                    <div class="col-md-12">
                        <h2 class="bordered">{{ __('Reset Password') }}</h2>
                    </div>
                </section>
            </div>

            <div class="row">
                <div class="col-md-5">
                    <section class="section">
                        <form class="contact-form cf-style-1 inner-top-xs" method="post" autocomplete="off" action="{{ route('password.request') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="field-row">
                                <label for="email">{{ __('E-Mail Address') }} *</label>
                                <input type="email" id="email" name="email" class="le-input{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ $email ?? old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div><!-- /.field-row -->

                            <div class="field-row">
                                <label for="password">{{ __('Password') }} *</label>
                                <input type="password" id="password" class="le-input{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div><!-- /.field-row -->

                            <div class="field-row">
                                <label for="password_confirmation">{{ __('Confirm Password') }} *</label>
                                <input id="password_confirmation" type="password" class="le-input" name="password_confirmation" value="" required>
                            </div><!-- /.field-row -->

                            <div class="buttons-holder">
                                <button type="submit" class="le-button huge"> {{ __('Reset Password') }}</button>
                            </div><!-- /.buttons-holder -->
                        </form><!-- /.contact-form -->
                    </section><!-- /.leave-a-message -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </main>
@endsection
