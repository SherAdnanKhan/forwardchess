@extends('layout.app')

@section('content')
  @include('site.parts.section.recommended-books')
  <shopping-cart
    products-url="{{ route('site.products.index') }}"
    checkout-url="{{ route('site.checkout') }}"
  ></shopping-cart>
@endsection
