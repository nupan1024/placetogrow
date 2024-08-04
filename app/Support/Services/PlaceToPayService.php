<?php

namespace App\Support\Services;

use App\Contracts\PaymentInterface;
use App\Domain\Payments\Actions\GetPayment;
use App\Domain\Payments\Actions\UpdateStatePayment;
use App\Domain\Transactions\Models\Transaction;
use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Message\RedirectResponse;
use Dnetix\Redirection\PlacetoPay;
use Illuminate\Support\Facades\Log;

class PlaceToPayService extends PaymentInterface
{
    private PlacetoPay $placetopay;

    public function __construct()
    {
        $this->placetopay = $this->init();
    }

    public function init(): PlacetoPay
    {
        $login = config('placetoplay.login');
        $tranKey = config('placetoplay.secret_key');
        $baseUrl = config('placetoplay.base_url');

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

    public function pay(array $payment): RedirectResponse|bool
    {
        $reference = 'microsite_placetopay'.$payment['data']['microsite_id'];
        $request = [
            'payment' => [
                'reference' => $reference,
                'description' => 'Testing payment',
                'amount' => [
                    'currency' => $payment['data']['currency'],
                    'total' => $payment['data']['value'],
                ],
            ],
            'expiration' => date('c', strtotime(' + 2 days')),
            'returnUrl' => route('payment.detail', $payment['transaction']->id),
            'ipAddress' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'userAgent' => "PlacetoPay Sandbox",
        ];
        $response = $this->placetopay->request($request);
        if ($response->status()->status() !== 'OK') {
            return false;
        }

        return $response;
    }

    public function getPaymentStatus(Transaction $transaction): array
    {
        $payment = GetPayment::execute(['transaction_id' => $transaction->id]);

        try {
            $statusPayment = $this->placetopay->query($payment->request_id);

            if (is_null($payment->status)) {
                UpdateStatePayment::execute([
                    'payment' => $payment,
                    'status' => $statusPayment->status()->status(),
                ]);
            }

            return [
                'status' => $statusPayment->status()->status(),
                'message' => $statusPayment->status()->message(),
            ];
        } catch (\Exception $e) {
            Log::channel('Payment')
                ->error('Error getting payment: '.$e->getMessage());
            return [
                'status' => Status::ST_ERROR, 'message' => $e->getMessage(),
            ];
        }
    }

}
