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
    <p> {{ __('invoices.updated_invoices_email', ['code' => $invoice->code]) }} 😉 </p>
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
        @if($data['status'] === $paid_status)
            <p>{{ __('invoices.paid_invoice', ['code' => $invoice->code]) }}</p>
        @elseif($data['status'] === $expired_status)
            <p>{{ __('invoices.expired_invoice', ['code' => $invoice->code]) }}</p>
        @endif
    @endisset
    <p class="signature">Regards, {{ config('app.name') }}</p>
</div>
</body>
</html>
