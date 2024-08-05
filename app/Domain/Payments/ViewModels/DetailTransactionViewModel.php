<?php

namespace App\Domain\Payments\ViewModels;

use App\Support\Services\Payments\Gateways\PlaceToPayService;
use App\Support\ViewModels\ViewModel;

class DetailTransactionViewModel extends ViewModel
{
    public function toArray(): array
    {
        /**
         * @var PlaceToPayService $placetopay
         */
        $placetopay = app(PlaceToPayService::class);
        return [
            'payment' => $this->model()->with(['microsite'])->find($this->model()->id),
            'status' => $placetopay->getPaymentStatus($this->model())
        ];
    }
}
