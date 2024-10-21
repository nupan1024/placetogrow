<?php

namespace App\Domain\Dashboard\Actions;

use App\Domain\Invoices\Models\Invoice;
use App\Support\Actions\Action;
use App\Support\Definitions\StatusInvoices;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GetTotalByPaidPendingInvoices implements Action
{
    public static function execute(array $params = [], $model = null): array
    {
        $cacheKey = 'dashboard_paid_pending_invoices';
        return Cache::remember($cacheKey, now()->addMinutes(60), function () {
            $invoices = Invoice::select(
                'status',
                DB::raw('SUM(value) as total_value')
            )
                ->whereIn('status', [
                    StatusInvoices::PAID->name,
                    StatusInvoices::PENDING->name
                ])
                ->groupBy('status')
                ->get()
                ->keyBy('status')
                ->toArray();

            return [
                [
                    'status' => StatusInvoices::PENDING->name,
                    'total_value' => $invoices[StatusInvoices::PENDING->name]['total_value'] ?? 0,
                ],
                [
                    'status' => StatusInvoices::PAID->name,
                    'total_value' => $invoices[StatusInvoices::PAID->name]['total_value'] ?? 0,
                ],
            ];

        });
    }
}
