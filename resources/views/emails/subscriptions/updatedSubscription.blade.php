<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        p {
            font-size: 12px;
        }

        .signature {
            font-style: italic;
        }
    </style>
</head>
<body>
<div>
    <p>Hey {{$name_user}},</p>
    <p> {{ __('subscriptions.updated_subject_title') }} {{ $subscription->microsite->name }} 😉 </p>
    @isset($params['name'])
        <p> {{ __('subscriptions.updated_name_email') }}</p>
        <p> {{ __('subscriptions.updated_name_email_label') }}: <b>{{ $params['name'] }}</b></p>
    @endisset
    @isset($params['description'])
        <p> {{ __('subscriptions.updated_description') }}</p>
        <p> {{ __('subscriptions.updated_description_label') }}: <b>{{ $params['description'] }}</b></p>
    @endisset
    @isset($params['amount'])
        <p> {{ __('subscriptions.updated_amount') }}</p>
        <p> {{ __('subscriptions.updated_amount_label') }}: <b>${{ $params['amount'] }}</b></p>
    @endisset
    @isset($params['billing_frequency'])
        <p> {{ __('subscriptions.updated_frequency') }}</p>
        <p> {{ __('subscriptions.updated_frequency_label') }}: <b>{{ $params['billing_frequency'] }}</b></p>
    @endisset
    @isset($params['status'])
        @if($params['status'] === 0)
            <p>{{ __('subscriptions.the_subscription') }} {{ $subscription->name }} {{ __('subscriptions.updated_disabled') }}.</p>
            <p>{{ __('subscriptions.updated_disabled_msj') }}: {{ $subscription->time_expire }}.</p>
        @else
            <p>{{ __('subscriptions.the_subscription') }} {{ $subscription->name }} {{ __('subscriptions.updated_enabled') }}.</p>
            <p>{{ __('subscriptions.updated_enabled_msj') }}</p>
        @endif
    @endisset
    <p class="signature">Regards, {{ config('app.name') }}</p>
</div>
</body>
</html>
