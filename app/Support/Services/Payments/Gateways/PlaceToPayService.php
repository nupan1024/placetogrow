<?php

namespace App\Support\Services\Payments\Gateways;

use App\Contracts\PaymentGateway;
use App\Domain\Payments\Models\Payment;
use App\Support\Definitions\PaymentStatus;
use App\Support\Services\Payments\PaymentResponse;
use App\Support\Services\Payments\QueryPaymentResponse;
use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\PlacetoPay;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class PlaceToPayService implements PaymentGateway
{
    private array $data;

    private array $config;

    public function __construct()
    {
        $this->data = [
            'expiration' => now()->addHour()->format('c'),
            'ipAddress' => request()->ip(),
            'userAgent' => request()->userAgent(),
        ];

        $this->config = config('gateways.placetopay');
    }

    public function init(): PlacetoPay
    {
        $login = $this->config['login'];
        $tranKey = $this->config['secret_key'];
        $baseUrl = $this->config['base_url'];

        if (!$login || !$tranKey || !$baseUrl) {
            Log::channel('Payment')
                ->error('Error getting payment: PlaceToPay configuration is incomplete.');
        }

        return new PlacetoPay([
            'login' => $login,
            'tranKey' => $tranKey,
            'baseUrl' => $baseUrl,
        ]);
    }

    public function buyer(array $data): self
    {
        $this->data['buyer'] = [
            'name' => $data['name'],
            'surname' => "",
            'email' => $data['email'],
            'documentType' => $data['type_document'],
            'document' => $data['num_document'],
        ];
        return $this;
    }

    public function payment(Payment $payment): self
    {
        $microsite = $payment->microsite()->with('currency')->first();
        $this->data['payment'] = [
            'reference' => $payment->reference,
            'description' => "",
            'amount' => [
                'currency' => $microsite->currency->name,
                'total' => $payment->value,
            ],
        ];

        $this->data['returnUrl'] = route('payment.detail', $payment);

        return $this;
    }

    public function subscription(Payment $payment): self
    {

        $this->data['subscription'] = [
            'reference' => $payment->reference,
            'description' => $payment->subscription->description,
        ];

        $this->data['expiration'] = $payment->subscription->time_expire;
        $this->data['returnUrl'] = route('payment.subscription.detail', $payment);

        return $this;
    }

    public function deleteSubscription($token): array
    {
        $this->data['instrument'] = [
             'token' => [
                 'token' => Crypt::decryptString($token),
             ],
         ];

        $placetopay = $this->init();
        $response = $placetopay->invalidateToken($this->data);

        if ($response['status']['status'] !== Status::ST_OK) {
            return [
                'status' => false,
                'message' => $response['status']['message'],
            ];
        }

        return [
            'status' => true,
        ];
    }

    public function process(): PaymentResponse
    {
        $placetopay = $this->init();
        $response = $placetopay->request($this->data);

        if (!$response->isSuccessful()) {
            Log::channel('Payment')->error($response->status()->message());
            return new PaymentResponse(
                '0',
                route('home'),
                PaymentStatus::REJECTED->name
            );
        }

        return new PaymentResponse(
            $response->requestId(),
            $response->processUrl(),
            $response->status()->status()
        );
    }

    public function getPaymentStatus(Payment $payment): QueryPaymentResponse
    {
        if (!isset($payment->request_id)) {
            return new QueryPaymentResponse('Payment not found', 'error');
        }

        return Cache::remember(
            'payment_status',
            now()->addMinutes(5),
            function () use ($payment) {
                $statusPayment = $this->init()->query($payment->request_id);
                return new QueryPaymentResponse(
                    $statusPayment->status()->message(),
                    $statusPayment->status()->status()
                );
            }
        );
    }

}
