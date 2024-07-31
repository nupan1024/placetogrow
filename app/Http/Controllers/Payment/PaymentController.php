<?php

namespace App\Http\Controllers\Payment;

use App\Domain\Payments\Actions\CreatePayment;
use App\Domain\Payments\ViewModels\DetailTransactionViewModel;
use App\Domain\Transactions\Actions\CreateTransaction;
use App\Domain\Transactions\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Support\Services\PaymentFactory;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Payments/List');
    }
    public function create(CreatePaymentRequest $request, PaymentFactory $paymentFactory)
    {
        $process = $paymentFactory->initializePayment('place_to_pay');
        $transaction = CreateTransaction::execute($request->validated());
        $placetopay = $process->pay([
            'data' => $request->validated(),
            'transaction' => $transaction
        ]);

        if (!$placetopay) {
            return redirect()->route('micrositio.form', $request['microsite_id'])
                ->with([
                'message' => __('payments.error'),
                'type' => 'error',
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
        return Inertia::render('Payment/Detail', new DetailTransactionViewModel($transaction));
    }

}
