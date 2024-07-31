<?php

namespace App\Domain\Payments\ViewModels;

use App\Support\Services\PlaceToPayService;
use App\Support\ViewModels\ViewModel;

class DetailTransactionViewModel extends ViewModel
{
    public function toArray(): array
    {
        $payment = new PlaceToPayService();
        return [
            'transaction' => $this->model()->with(['microsite'])->find($this->model()->id),
            'status' => $payment->getPaymentStatus($this->model())
        ];
    }
}
