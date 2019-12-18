@component('mail::message')
# Forwarder Disabled

The forwarder for {{ $webhook->to }} has been disabled as the last notification attempt was unsuccessful.

@component('mail::button', ['url' => route('home')])
Re-enable
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
