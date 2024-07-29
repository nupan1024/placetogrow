<?php

namespace App\Http\Controllers\Payment;

use App\Domain\Payments\Actions\CreatePayment;
use App\Domain\Transactions\Actions\CreateTransaction;
use App\Domain\Transactions\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Support\Services\PaymentFactory;
use App\Support\ViewModels\HomeViewModel;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    public function create(
        CreatePaymentRequest $request,
        PaymentFactory $paymentFactory
    ) {
        $process = $paymentFactory->initializePayment('place_to_pay');
        $transaction = CreateTransaction::execute($request->validated());
        $placetopay = $process->pay([
            'data' => $request->validated(),
            'transaction' => $transaction
        ]);

        if (!$placetopay) {
            return redirect()->route('micrositio.form', $request['microsite_id'])
                ->with([
                'message' => __('roles.success_create'),
                'type' => 'success',
            ]);
        }
        $payment = $placetopay->toArray();
        CreatePayment::execute([
            'payment' => $payment,
            'transaction_id' => $transaction->id
        ]);
        return Inertia::location($placetopay->processUrl());
    }

    public function detail(Transaction $transaction): Response
    {
        return Inertia::render('Payments/Detail', new HomeViewModel());
    }

}
