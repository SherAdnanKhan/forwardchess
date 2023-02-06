@component('mail::message')
# Hello {{ $gift->friendName }},

Good news! You received a **${{ toFloatAmount($gift->amount) }}** gift card from {{ $order->user->fullName }} to use at the <a href="{{$url}}">Forward Chess</a> webstore.

The gift card is available for any purchase until {{ $gift->formatDate($gift->expireDate, 'd M, Y') }}.

@if ($gift->friendMessage)
Here is your friend's message for you:
@component('mail::panel')
   {{ $gift->friendMessage }}
@endcomponent
@endif

To use it, just enter the following code in the shopping cart page.

@component('mail::subcopy')
   <h1 style="text-align: center; font-size: 35px;">{{ $gift->code }}</h1>
@endcomponent

@component('mail::button', ['url' => $url, 'color' => 'success'])
Start shopping here
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
