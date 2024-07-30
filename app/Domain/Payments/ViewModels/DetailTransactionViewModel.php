<?php

namespace App\Domain\Payments\ViewModels;

use App\Domain\Payments\Models\Payment;
use App\Support\ViewModels\ViewModel;

class DetailTransactionViewModel extends ViewModel
{
    public function toArray(): array
    {
        return [
            'transaction' => $this->model()->with(['microsite'])->find($this->model()->id),
            'payment' => Payment::where('transaction_id', $this->model()->id)->first(),
        ];
    }
}
