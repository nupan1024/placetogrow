<?php

namespace App\Validators;

use App\Domain\Invoices\Actions\GetInvoiceByCode;
use App\Support\Definitions\StatusInvoices;
use DateTime;

use function is_numeric;

class InvoiceValidator
{
    protected array $errors = [];

    public function __construct()
    {
        $this->errors = [];
    }

    public function validate(array $row, int $line): void
    {
        $this->errors = [];

        if (!isset($row['code'])) {
            $this->setError('The field code is required', $line);
        }

        if (GetInvoiceByCode::execute(['code' => $row['code']])) {
            $this->setError('Invoice already exists', $line);
        }

        if (!isset($row['value']) || !is_numeric($row['value'])) {
            $this->setError('Invalid amount format', $line);
        }

        if (!isset($row['email']) || !filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
            $this->setError('Invalid email format', $line);
        }

        if (! isset($row['customer_name']) || strlen($row['customer_name']) > 100) {
            $this->setError('Invalid customer name format', $line);
        }

        if (!isset($row['description']) || strlen($row['description']) > 512) {
            $this->setError('Invalid description format', $line);
        }

        if (!isset($row['date_expire_pay']) || !$this->validateDate($row['date_expire_pay'])) {
            $this->setError('Invalid date_expire_pay format', $line);
        }

        $status = StatusInvoices::asOptions();
        $nameStatus = array_column($status, 'name');
        if (!isset($row['status']) || !in_array(ucfirst(strtolower($row['status'])), $nameStatus)) {
            $this->setError('Invalid status format', $line);
        }

        if ($row['status'] ===  StatusInvoices::PENDING->name && $row['date_expire_pay'] < now()->addDays(3)->format('Y-m-d')) {
            $this->setError('The field date_expire_pay must be greater than 3 days from today', $line);
        }
    }

    public function setError(string $error, int $line): void
    {
        $this->errors[] = "Line #{$line}, error: {$error}";
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }
    public function validateDate($date, $format = 'Y-m-d'): bool
    {
        $formatDate = DateTime::createFromFormat($format, $date);
        return $formatDate && $formatDate->format($format) === $date;
    }

}
