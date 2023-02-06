@component('mail::message')
# Hello {{ $order->user->fullName }},

This is the payment confirmation for order **{{ $order->refNo }}**.

**Order date**: {{ $order->getCreatedAtFormatted('m/d/Y') }}

**Customer details:**

{{ $order->billing->firstName }} {{ $order->billing->lastName }}
{{ $order->billing->countryName() }}

@component('mail::table')
| #             | Product       | Price    |
| :------------ |:--------------| --------:|
@foreach ($order->items as $index => $item)
| **{{ $index + 1 }}.** | {{ $item->name }} | ${{ $item->total }} |
@endforeach
@endcomponent

**Subtotal:** ${{ $order->subTotal }}

@if ($order->discount)
**Discount:** ${{ $order->discount }}
@endif

@if ($order->taxAmount)
**Tax**: ${{ $order->taxAmount }}
@endif

## **Total:** ${{ $order->total }}

@component('mail::button', ['url' => $url, 'color' => 'success'])
See full details here
@endcomponent


New Order: Need help downloading?

Go to <a href="{{ $instructions }}">{{ $instructions }}</a>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
