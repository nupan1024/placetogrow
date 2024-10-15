<?php

namespace App\Http\Controllers\Payment;

use App\Domain\Payments\Actions\CreatePayment;
use App\Domain\Payments\Actions\UpdatePayment;
use App\Domain\Payments\Models\Payment;
use App\Domain\Payments\ViewModels\DetailSubscriptionViewModel;
use App\Domain\Payments\ViewModels\DetailTransactionViewModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Contracts\PaymentService;
use App\Support\Definitions\StatusInvoices;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class PaymentController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Payments/List');
    }
    public function create(CreatePaymentRequest $request): SymfonyResponse
    {
        $payment = CreatePayment::execute($request->validated());
        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class, [
            'payment' => $payment,
            'gateway' => $request->gateway,
        ]);
        $placetopay = $paymentService->create($request->validated());

        UpdatePayment::execute([
            'url' => $placetopay->url,
            'request_id' => $placetopay->processIdentifier,
            'status' => StatusInvoices::PENDING->name,
        ], $payment);

        return Inertia::location($placetopay->url);
    }

    public function detail(Payment $payment): Response
    {
        return Inertia::render(
            'Payment/Detail',
            new DetailTransactionViewModel($payment)
        );
    }
    public function subscriptionDetail(Payment $payment): Response
    {
        return Inertia::render(
            'Payment/Subscription/Detail',
            new DetailSubscriptionViewModel($payment)
        );
    }
}
