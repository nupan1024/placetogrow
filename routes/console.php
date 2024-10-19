<?php

use App\Jobs\ProcessUpdateInvoicesToExpired;
use App\Jobs\ProcessPayments;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new ProcessPayments())->everyFifteenSeconds();
Schedule::job(new ProcessUpdateInvoicesToExpired())->daily();
