<?php

namespace App\Domain\Microsites\ViewModels;

use App\Domain\Fields\Actions\GetJsonFields;
use App\Domain\Invoices\Actions\GetInvoicesByMicrositeAndUser;
use App\Support\Definitions\DocumentsTypes;
use App\Support\Definitions\MicrositesTypes;
use App\Support\ViewModels\ViewModel;

class FormMicrosite extends ViewModel
{
    public function toArray(): array
    {
        /**
         * @var \App\Domain\Microsites\Models\Microsite $microsite
         */
        $microsite = $this->model()->with(['category', 'type', 'currency'])->find($this->model()->getKey());
        $invoices = [];
        if ($microsite->date_expire_pay &&
            $microsite->date_expire_pay >= now() &&
            $microsite->microsites_type_id === MicrositesTypes::INVOICE->value) {
            $invoices = GetInvoicesByMicrositeAndUser::execute([
                'microsite_id' => $microsite->id,
                'user_id' => auth()->user()->id ?? ""
            ]);
        }

        return [
            'microsite' => $microsite,
            'fields' => GetJsonFields::execute($microsite->fields),
            'documents' => DocumentsTypes::toArray(),
            'invoices' => $invoices,
        ];
    }
}
