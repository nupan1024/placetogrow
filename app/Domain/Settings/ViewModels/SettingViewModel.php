<?php

namespace App\Domain\Settings\ViewModels;

use App\Domain\Settings\Models\Setting;
use App\Support\ViewModels\ViewModel;

class SettingViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Setting());
    }
    public function toArray(): array
    {
        return [
            'attempts' => Setting::where('key', 'attempts')->first(),
            'period_time' => Setting::where('key', 'period_time')->first(),
            'invoice_penalty' => Setting::where('key', 'invoice_penalty')->first(),
        ];
    }
}
