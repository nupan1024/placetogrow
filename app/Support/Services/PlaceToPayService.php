<?php

namespace App\Support\Services;

use App\Contracts\PaymentInterface;
use Dnetix\Redirection\Message\RedirectResponse;
use Dnetix\Redirection\PlacetoPay;

class PlaceToPayService extends PaymentInterface
{
    private static PlacetoPay $placetopay;

    public function __construct()
    {
        self::$placetopay = $this->setUpPayment();
    }

    public function pay(array $payment): RedirectResponse|bool
    {
        $reference = 'microsite_placetopay' . $payment['data']['microsite_id'];
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
            'userAgent' => "PlacetoPay Sandbox"
        ];
        $response = self::$placetopay->request($request);
        if ($response->status()->status() !== 'OK') {
            return false;
        }

        return $response;
    }

    public function getPaymentStatus(string $invoice_id): string
    {
        return false;
    }

}
