<?php

namespace App\Domain\Microsites\ViewModels;

use App\Support\ViewModels\ViewModel;

class ListByMicrositeViewModel extends ViewModel
{
    public function toArray(): array
    {
        return [
            'microsite' => $this->model(),
        ];
    }
}
