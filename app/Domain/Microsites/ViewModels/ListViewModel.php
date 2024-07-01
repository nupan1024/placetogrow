<?php

namespace App\Domain\Microsites\ViewModels;

use App\Domain\Microsites\Models\Microsite;
use App\Support\ViewModels\ViewModel;

class ListViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Microsite());
    }

    public function toArray(): array
    {
        return [
            'microsites' => Microsite::with(['category', 'type'])->get(),
        ];
    }
}
