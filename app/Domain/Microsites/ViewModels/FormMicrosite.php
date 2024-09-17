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
        $data = [
            'microsite' => $microsite,
            'fields' => GetJsonFields::execute($microsite->fields),
            'documents' => DocumentsTypes::toArray(),
        ];

        switch ($microsite->microsites_type_id) {
            case MicrositesTypes::INVOICE->value:
                if ($microsite->date_expire_pay >= now()) {
                    $invoices = GetInvoicesByMicrositeAndUser::execute([
                        'microsite_id' => $microsite->id,
                        'user_id' => auth()->user()->id ?? ""
                    ]);

                    $data['invoices'] = $invoices;
                }
                break;
            case MicrositesTypes::SUBSCRIPTIONS->value:
                $subscriptions = $microsite->subscriptions()->get();
                $data['subscriptions'] = $subscriptions;
                break;
        }

        return $data;
    }
}
