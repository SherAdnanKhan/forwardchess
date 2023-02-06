@extends('layout.app')

@section('head-scripts')
    {!! htmlScriptTagJsApi(['form_id' => 'contact-form']) !!}
@endsection

@section('content')
    <main id="contact-us" class="contact-us p-t-105 p-b-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title">
                        Contact us
                    </div>
                    <div class="primary-description m-b-40">
                        Use the form below to contact us. We are happy to help you with any kind of information.
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-9 col-order">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <section class="section-contact-form">
                        @if(Session::has('success-message'))
                            <div class="text-success ">{{ Session::get('success-message') }}</div>
                        @endif

                        <form id="{{ getFormId() }}" class="contact-form" method="post">
                            <div class="primary-title m-b-20">
                                Leave your message
                            </div>
                            @csrf

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-responsive-margin col-padding-right">
                                    <label class="description m-b-10">Your Name</label>
                                    <input id="name"
                                           type="text"
                                           name="name"
                                           placeholder="Enter Your Name"
                                           class="secondary-description le-input{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           value="{{ old('name') }}"
                                           required
                                    >
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xs-12 col-sm-6 col-padding-left">
                                    <label class="description m-b-10">Your Email</label>
                                    <input id="email"
                                           type="email"
                                           placeholder="Enter Your Email"
                                           name="email"
                                           class="secondary-description le-input{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           value="{{ old('email') ? old('email') : $email  }}"
                                           required
                                    >
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="field-row m-t-30">
                                <label class="description m-b-10">Subject</label>
                                <input id="subject"
                                       type="text"
                                       name="subject"
                                       class="secondary-description le-input{{ $errors->has('subject') ? ' is-invalid' : '' }}"
                                       value="{{ old('subject') }}"
                                       required>

                                @if ($errors->has('subject'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="field-row m-t-30">
                                <label class="description m-b-10">Your Message</label>
                                <textarea id="message"
                                          rows="8"
                                          placeholder="Enter Your Message (min. 5 characters)"
                                          name="message"
                                          class="secondary-description le-input le-texteria{{ $errors->has('message') ? ' is-invalid' : '' }}"
                                          required
                                >{{ old('message') }}</textarea>
                                @if ($errors->has('message'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="buttons-holder d-flex justify-content-end">
                                {!! htmlFormButton('Send Message', ['class' => 'le-button contact-button']) !!}
                            </div>
                        </form>
                    </section>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                    <section class="section-contact-address">
                        <div class="primary-title m-b-20">
                            Our contacts
                        </div>
                        <address>
                            <div class="wrapper-address-block d-flex">
                                <div class="icon">
                                    <svg width="14" height="19" viewBox="0 0 14 19" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7 9.025C6.33696 9.025 5.70107 8.77478 5.23223 8.32938C4.76339 7.88398 4.5 7.27989 4.5 6.65C4.5 6.02011 4.76339 5.41602 5.23223 4.97062C5.70107 4.52522 6.33696 4.275 7 4.275C7.66304 4.275 8.29893 4.52522 8.76777 4.97062C9.23661 5.41602 9.5 6.02011 9.5 6.65C9.5 6.96189 9.43534 7.27073 9.3097 7.55887C9.18406 7.84702 8.99991 8.10884 8.76777 8.32938C8.53562 8.54992 8.26002 8.72486 7.95671 8.84421C7.65339 8.96357 7.3283 9.025 7 9.025ZM7 0C5.14348 0 3.36301 0.700623 2.05025 1.94774C0.737498 3.19486 0 4.88631 0 6.65C0 11.6375 7 19 7 19C7 19 14 11.6375 14 6.65C14 4.88631 13.2625 3.19486 11.9497 1.94774C10.637 0.700623 8.85652 0 7 0Z"
                                            fill="#757575"/>
                                    </svg>
                                </div>
                                <div class="description description-address m-b-10">
                                    WiTechnoME, LLC
                                    110 Marginal Way, Suite 260
                                    Portland, ME 04101 USA
                                </div>
                            </div>
                            <div class="wrapper-address-block d-flex">
                                <div class="icon d-flex justify-content-center m-t-3">
                                    <svg width="18" height="14" viewBox="0 0 18 14" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16.2 3.5L9 7.875L1.8 3.5V1.75L9 6.125L16.2 1.75V3.5ZM16.2 0H1.8C0.801 0 0 0.77875 0 1.75V12.25C0 12.7141 0.189642 13.1592 0.527208 13.4874C0.864773 13.8156 1.32261 14 1.8 14H16.2C16.6774 14 17.1352 13.8156 17.4728 13.4874C17.8104 13.1592 18 12.7141 18 12.25V1.75C18 0.77875 17.19 0 16.2 0Z"
                                            fill="#757575"/>
                                    </svg>
                                </div>
                                <div class="description m-l-10">
                                    <a class="description descr-link" href="mailto:Info@forwardchess.com">Info@forwardchess.com</a>
                                </div>
                            </div>
                        </address>
                    </section>
                </div>
            </div>
        </div>
    </main>
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
