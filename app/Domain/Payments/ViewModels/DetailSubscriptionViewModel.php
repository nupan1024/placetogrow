<?php

namespace App\Domain\Payments\ViewModels;

use App\Domain\Payments\Models\Payment;
use App\Support\Services\Payments\Gateways\PlaceToPayService;
use App\Support\ViewModels\ViewModel;

/**
 * @extends ViewModel<Payment>
 */
class DetailSubscriptionViewModel extends ViewModel
{
    public function toArray(): array
    {
        /**
         * @var PlaceToPayService $placetopay
         */
        $placetopay = app(PlaceToPayService::class);
        return [
            'payment' => $this->model()
                ->with(['microsite'])
                ->find($this->model()->getKey()),
            'status' => $placetopay->getPaymentStatus($this->model()),
        ];
    }

}
