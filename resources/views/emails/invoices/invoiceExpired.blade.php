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
        <h1>¡¡Tu factura se encuentra vencida!!</h1>
    </div>

    <div class="content">
        <p>Hola <strong>{{ $user->name }}</strong>,</p>
        <p>Queremos informarte que tu factura <strong>#{{ $invoice->code }}</strong> se encuentra <strong>VENCIDA</strong> por esta razón se le ha incrementado el total, nuevo total: <strong>${{ $invoice->value }}</strong> .</p>
        <p>Por favor, realiza el pago lo más pronto posible.</p>

        <div class="cta">
            <a href="{{ route('form.microsite', $invoice->microsite_id) }}">Pagar</a>
        </div>
        <p>Si presentar dificultades para pagar, por favor contacta con nuestro equipo<br>
            <a href="mailto:info@placetogrow.com">info@placetogrow.com</a>
        </p>
        <p>Si ya realizaste el pago, por favor ignora este mensaje.</p>
    </div>

    <div class="footer">
        <p>Gracias por confiar en nosotros.</p>
        <p><a href="{{ route('home') }}">Visita nuestro sitio web</a></p>
    </div>
</div>
</body>
</html>

