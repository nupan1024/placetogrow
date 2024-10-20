<?php

use App\Jobs\ProcessSubscriptionCollect;
use App\Jobs\ProcessUpdateInvoicesToExpired;
use App\Jobs\ProcessPayments;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new ProcessPayments())->everyFiveMinutes();
Schedule::job(new ProcessUpdateInvoicesToExpired())->daily();
Schedule::job(new ProcessSubscriptionCollect())->daily();
