<?php

namespace App\Support\ViewModels;

use App\Domain\Microsites\Models\Microsite;
use Illuminate\Support\Facades\Route;

class HomeViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Microsite());
    }

    public function toArray(): array
    {
        return [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'microsites' => Microsite::with(['category', 'type'])->get(),
        ];
    }
}
