<?php

namespace App\Domain\Microsites\ViewModels;

use App\Domain\Microsites\Models\Microsite;
use App\Support\ViewModels\ViewModel;

class FormMicrosite extends ViewModel
{
    public function __construct($id)
    {
        $microsite = Microsite::with(['category', 'type', 'currency'])->find($id);
        parent::__construct($microsite ?: new Microsite());
    }

    public function toArray(): array
    {
        return [
            'microsite' => $this->model(),
        ];
    }
}
