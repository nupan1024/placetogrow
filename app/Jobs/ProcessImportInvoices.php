<?php

namespace App\Jobs;

use App\Domain\Imports\Actions\UpdateImport;
use App\Domain\Imports\Models\Import;
use App\Domain\Users\Actions\GetUserByEmail;
use App\Support\Definitions\ImportStatus;
use App\Support\Services\Imports\CsvReader;
use App\Validators\InvoiceValidator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessImportInvoices implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    private int $line = 0;

    private array $rows = [];

    private array $errors = [];

    private array $headers = [];

    private InvoiceValidator $validator;

    public function __construct(protected Import $import, protected int $microsite_id)
    {
        $this->validator = new InvoiceValidator();
    }

    public function handle(): void
    {
        try {
            $reader = new CsvReader($this->import->getFullPath());
            foreach ($reader->rows() as $row) {
                $this->line++;
                if ($this->line === 1) {
                    $this->headers = $row;

                    $this->headers[] = 'line';
                    $this->headers[] = 'import_id';
                    continue;
                }

                $row[] = $this->line;
                $row[] = $this->import->id;
                $row = array_combine($this->headers, $row);
                $row['value'] = str_replace(['$', '.'], '', trim($row['value']));

                $this->validate($row);
                if (!$this->hasErrors()) {
                    $user = GetUserByEmail::execute([
                        'email' => $row['email']
                    ]);

                    $this->rows[] = [
                        'microsite_id' => $this->microsite_id,
                        'user_id' => $user->id,
                        'value' => $row['value'],
                        'description' => $row['description'],
                        'code' => $row['code'],
                        'status' => $row['status']
                    ];
                }
            }

            if ($this->hasErrors()) {
                $this->setImportErrors();
                Log::channel('Imports')->error('There was a error processing invoices, line: ' . $this->line);
                return;
            }

            if ($this->hasRows()) {
                $this->insert();
            }
        } catch (Throwable $th) {
            $this->failed($th);
            Log::channel('Invoices')->error('Error processing invoices: '.$th->getMessage());
        }
    }

    public function failed(?Throwable $exception = null): void
    {
        if ($exception) {
            UpdateImport::execute([
                'errors' => Arr::wrap('TEST'. $exception->getMessage()),
                'status' => ImportStatus::FAILED->value
            ], $this->import);
        }
    }
    private function validate(array $row): void
    {
        $this->validator->validate($row, $this->line);

        if ($this->validator->fails()) {
            $this->errors[] = $this->validator->getErrors();
        }
    }

    private function hasErrors(): bool
    {
        return ! $this->isSuccessful();
    }
    private function isSuccessful(): bool
    {
        return empty($this->errors);
    }
    private function setImportErrors(): void
    {
        UpdateImport::execute([
            'errors' => $this->errors,
            'status' => ImportStatus::FAILED->value
        ], $this->import);
    }
    private function insert(): void
    {
        $data = collect($this->rows);
        $chunks = $data->chunk(500);
        DB::beginTransaction();

        try {
            foreach ($chunks as $chunk) {
                DB::table('invoices')->insert($chunk->toArray());
            }
            DB::commit();
            UpdateImport::execute([
                'errors' => $this->errors,
                'status' => ImportStatus::COMPLETED->value
            ], $this->import);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error inserting data into invoices table: ' . $e->getMessage());
            UpdateImport::execute([
                'errors' => Arr::wrap($e->getMessage()),
                'status' => ImportStatus::FAILED->value
            ], $this->import);
        }
    }
    private function hasRows(): bool
    {
        return ! empty($this->rows) && $this->isSuccessful();
    }
}
