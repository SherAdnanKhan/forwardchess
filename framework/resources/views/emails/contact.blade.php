@component('mail::message')
# Hello,

This is a contact message from {{ config('app.name') }}.

**Name**: {{ $message->name }}

**Email**: {{ $message->email }}

**Message**: {{ $message->message }}

Thanks,<br>
{{ config('app.name') }}

@endcomponent
