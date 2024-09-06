<?php

namespace App\Imports;

use App\Domain\Invoices\Models\Invoice;
use Maatwebsite\Excel\Concerns\ToModel;

class InvoicesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Invoice([
            'microsite_id' => $row[0],
            'user_id' => $row[1],
            'value' => $row[2],
            'description' => $row[3],
            'status' => $row[4],
        ]);
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'value' => ['required', 'numeric'],
            'description' => ['required', 'string'],
            'status' => ['required', 'boolean'],
        ];
    }
}
