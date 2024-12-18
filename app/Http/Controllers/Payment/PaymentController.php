<?php

namespace App\Http\Controllers\Payment;

use App\Domain\Payments\Actions\CreatePayment;
use App\Domain\Payments\Actions\UpdatePayment;
use App\Domain\Payments\Actions\UpdatePaymentWithPaymentTypes;
use App\Domain\Payments\Models\Payment;
use App\Domain\Payments\ViewModels\DetailSubscriptionViewModel;
use App\Domain\Payments\ViewModels\DetailTransactionViewModel;
use App\Domain\SubscriptionUser\Actions\ValidateIfSubscriptionExist;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Contracts\PaymentService;
use App\Support\Definitions\PaymentStatus;
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
        $validation = ValidateIfSubscriptionExist::execute($request->validated());
        if (!$validation && $request->get('subscription_id')) {
            return redirect()->route('home')
                ->with([
                    'message' => __('subscriptions.active_subscription'),
                    'type' => 'error',
                ]);
        }

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
        if ($payment->status === PaymentStatus::PENDING->value) {
            UpdatePaymentWithPaymentTypes::execute([], $payment);
        }

        return Inertia::render(
            'Payment/Detail',
            new DetailTransactionViewModel($payment)
        );
    }
    public function subscriptionDetail(Payment $payment): Response
    {
        if ($payment->status === PaymentStatus::PENDING->value) {
            UpdatePaymentWithPaymentTypes::execute([], $payment);
        }

        return Inertia::render(
            'Payment/Subscription/Detail',
            new DetailSubscriptionViewModel($payment)
        );
    }
}
