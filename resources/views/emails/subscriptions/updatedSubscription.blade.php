@php use App\Support\Definitions\Status; @endphp
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Factura</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px 30px;
            line-height: 1.6;
            color: #333;
        }

        .content p {
            margin: 10px 0;
        }

        .content .cta {
            margin: 20px 0;
            text-align: center;
        }

        .cta a {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .cta a:hover {
            background-color: #45a049;
        }

        .footer {
            background-color: #f1f1f1;
            color: #555;
            text-align: center;
            padding: 15px;
            font-size: 14px;
        }

        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1> Tu suscripción ha sido actualizada</h1>
    </div>

    <div class="content">
        <p>Hola <strong>{{ $name_user }}</strong>,</p>
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
            @if($params['status'] === Status::INACTIVE->value)
                <p>{{ __('subscriptions.the_subscription') }} {{ $subscription->name }} {{ __('subscriptions.updated_disabled') }}
                    .</p>
                <p>{{ __('subscriptions.updated_disabled_msj') }}: {{ $subscription->time_expire }}.</p>
            @else
                <p>{{ __('subscriptions.the_subscription') }}
                    <b>{{ $subscription->name }}</b> {{ __('subscriptions.updated_enabled') }}</p>
                <p>{{ __('subscriptions.updated_enabled_msj') }}</p>
            @endif
        @endisset
    </div>

    <div class="footer">
        <p>Gracias por confiar en nosotros.</p>
        <p><a href="{{ route('home') }}">Visita nuestro sitio web</a></p>
    </div>
</div>
</body>
</html>

