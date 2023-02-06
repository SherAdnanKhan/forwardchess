@component('mail::message')
# Hello {{ $order->user->fullName }},

Due to payment inactivity on the order **{{ $order->refNo }}**, we refunded you the gift card amounts that you used.

@component('mail::button', ['url' => $url, 'color' => 'success'])
    Start shopping
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
