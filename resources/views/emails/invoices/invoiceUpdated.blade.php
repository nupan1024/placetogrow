@php use App\Support\Definitions\StatusInvoices; @endphp
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
        <h1> Tu factura ha sido actualizada</h1>
    </div>

    <div class="content">
        <p>Hola <strong>{{ $user->name }}</strong>,</p>
        <p>{{ __('invoices.updated_invoices_email', ['code' => $invoice->code]) }}.</p>

        @isset($data['date_expire_pay'])
            <p> {{ __('invoices.title_date_expire') }}</p>
            <p> {{ __('invoices.new_date_expire', ['expire' => $data['date_expire_pay']]) }}</p>
        @endisset

        @isset($data['description'])
            <p>{{ __('invoices.title_description') }}</p>
            <p>{{ __('invoices.new_description', ['description' => $data['description']]) }}</p>
        @endisset

        @isset($data['value'])
            <p>{{ __('invoices.title_value') }}</p>
            <p> {{ __('invoices.new_value', ['value' => $data['value']]) }}</p>
        @endisset

        @isset($data['status'])
            @if($data['status'] === StatusInvoices::PAID->name)
                <p>{{ __('invoices.paid_invoice', ['code' => $invoice->code]) }}</p>
            @else
                <p>{{ __('invoices.expired_invoice', ['code' => $invoice->code]) }}</p>

                <div class="cta">
                    <a href="{{ route('form.microsite', $invoice->microsite_id) }}">Pagar</a>
                </div>

                <p>Si ya realizaste el pago, por favor ignora este mensaje.</p>
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
