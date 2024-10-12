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
    <p>Hey {{$user->name}},</p>
    <p>{{ __('invoices.delete_invoices_email', ['code' => $invoice['code']]) }} 😉 </p>
    <p class="signature">Regards, {{ config('app.name') }}</p>
</div>
</body>
</html>
