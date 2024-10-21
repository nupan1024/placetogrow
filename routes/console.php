<?php

use App\Jobs\ProcessSubscriptionCollect;
use App\Jobs\ProcessInvoices;
use App\Jobs\ProcessPayments;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new ProcessPayments())->everyFiveMinutes();
Schedule::job(new ProcessInvoices())->daily();
Schedule::job(new ProcessSubscriptionCollect())->daily();
