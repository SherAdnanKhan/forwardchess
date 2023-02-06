@extends('layout.app')

@section('content')
    <buy-gift-card
            form-action="{{ route('site.placeGiftOrder') }}"
            gift-details="{{ $gift }}"
            billing="{{ $billing }}"
    ></buy-gift-card>
@endsection
