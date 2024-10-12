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
    <p>{{ $invoice->code  }}</p>
    <p>{{ __('invoices.description_invoice_email') }}:  {{ $invoice->description }} 😉 </p>
    <p>{{ __('invoices.value') }}: ${{ $invoice->value }}</p>
    <p>{{ __('labels.status') }}: {{ $invoice->status }}</p>
    @if($invoice->status === 'PENDING')
        <p>{{ __('labels.date_pay') }}: {{ $invoice->date_expire_pay }}</p>
        <p><a href="{{ route('form.microsite', $invoice->microsite_id) }}">{{ __('invoices.pay') }}</a></p>
    @endif
    <p class="signature">Regards, {{ config('app.name') }}</p>
</div>
</body>
</html>
