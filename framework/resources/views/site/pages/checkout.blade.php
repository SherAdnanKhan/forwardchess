@extends('layout.app')

@section('content')
  <place-order
    form-action="{{ route('site.placeOrder') }}"
    billing="{{ $billing }}"
  ></place-order>
@endsection
