@extends('layout.app')

@section('content')

    <div id="section-gift-cards" class="section-gift-cards p-t-105">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="title m-b-40">
                        Gift Cards
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <div class="row d-flex flex-column">
                        <div class="col-lg-12 col-order">
                            <div class="primary-title m-b-15 m-t-24">
                                Not sure what to give as a present?
                            </div>
                            <div class="description m-b-30">
                                You can choose a gift card of any value to please a friend or family member.
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <div class="wrapper-image">
                                <img src="{{ asset('images/Ticket 2.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="wrapper-gift-cards-form">
                        <form class="" method="post">
                            @csrf
                            <div class="wrapper-form">
                                <div class="primary-title m-b-25">
                                    Set up your gift card
                                </div>

                                <div class="wrapper-form-name d-flex justify-content-between">
                                    <div class="form-name">
                                        <label class="description m-b-10">Recipient’s Name</label>
                                        <input id="name"
                                               type="text"
                                               name="name"
                                               placeholder="Enter Recipient’s Name"
                                               class="capital le-input{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               value="{{ old('name') ? old('name') : $gift['name'] }}" required>

                                        @if ($errors->has('name'))
                                            <span class="error m-b-10">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-email">
                                        <label class="description m-b-10">Recipient’s Email</label>
                                        <input id="email"
                                               type="email"
                                               name="email"
                                               placeholder="Enter Recipient’s Email"
                                               class="le-input{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                               value="{{ old('email') ? old('email') : $gift['email'] }}"
                                               required
                                        >

                                        @if ($errors->has('email'))
                                            <span class="error m-b-10">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-description">
                                    <label class="description m-b-10">Your message</label>
                                    <textarea rows="8"
                                              id="message"
                                              placeholder="Enter Your Message (min. 5 characters)"
                                              name="message"
                                              class="m-b-10 le-texteria le-input{{ $errors->has('message') ? ' is-invalid' : '' }}">{{ old('message') ? old('message') : $gift['message'] }}</textarea>

                                    @if ($errors->has('message'))
                                        <span class="error m-b-10">
                                            <strong>{{ $errors->first('message') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="wrapper-buttons">
                                    <label class="description m-b-10">Card Amount</label>
                                    <div class="wrapper-input-button d-flex align-items-center justify-content-between m-b-10">
                                        <div class="input-group">
                                            <input type="text" id="amount"
                                                   placeholder="Enter Card Amount"
                                                   name="amount"
                                                   class="le-input{{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                                   required>
                                        </div>

                                        <div class="buttons-holder">
                                            <button type="submit"
                                                    class="le-button">Buy Now
                                            </button>
                                        </div>
                                    </div>

                                    @if ($errors->has('amount'))
                                        <span class="error">
                                            <strong>{{ $errors->first('amount') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
