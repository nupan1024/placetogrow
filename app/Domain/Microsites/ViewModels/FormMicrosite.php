<?php

namespace App\Domain\Microsites\ViewModels;

use App\Support\ViewModels\ViewModel;

class FormMicrosite extends ViewModel
{
    public function toArray(): array
    {
        return [
            'microsite' => $this->model()->with(['category', 'type', 'currency'])->find($this->model()->id),
        ];
    }
}
