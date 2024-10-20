<?php

namespace App\Domain\Payments\ViewModels;

use App\Domain\Payments\Models\Payment;
use App\Support\ViewModels\ViewModel;

/**
 * @extends ViewModel<Payment>
 */
class DetailSubscriptionViewModel extends ViewModel
{
    public function toArray(): array
    {
        return [
            'payment' => $this->model()
                ->with(['microsite'])
                ->find($this->model()->getKey()),
        ];
    }

}
