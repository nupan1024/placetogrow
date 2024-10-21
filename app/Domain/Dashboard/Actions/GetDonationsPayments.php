<?php

namespace App\Domain\Dashboard\Actions;

use App\Domain\Payments\Models\Payment;
use App\Support\Actions\Action;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GetDonationsPayments implements Action
{
    public static function execute(array $params = [], $model = null): array
    {
        $cacheKey = 'dashboard_donations_payments';
        return Cache::remember($cacheKey, now()->addMinutes(60), function () {
            $startDate = Carbon::now()->subDays(7);

            return Payment::select(
                DB::raw('DATE_FORMAT(created_at, "%W") as day'),
                DB::raw('SUM(value) as total_value')
            )
                ->whereNull('invoice_id')
                ->whereNull('subscription_id')
                ->where('status', 'APPROVED')
                ->where('created_at', '>=', $startDate)
                ->groupBy('day')
                ->orderBy(DB::raw('FIELD(day, "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday")'))
                ->get()
                ->toArray();
        });
    }
}
