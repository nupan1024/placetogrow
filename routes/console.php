<?php

use App\Jobs\ProcessPaymentCollect;
use App\Jobs\UpdateInvoicesToExpired;
use App\Jobs\UpdateStatusPayments;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new UpdateStatusPayments())->everyFiveMinutes();
Schedule::job(new ProcessPaymentCollect())->everyFiveMinutes();
Schedule::job(new UpdateInvoicesToExpired())->daily();
