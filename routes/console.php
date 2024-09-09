<?php

use App\Jobs\ProcessUpdateSubscriptionUser;
use App\Jobs\UpdateStatusInvoices;
use App\Jobs\UpdateStatusPayments;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new UpdateStatusPayments())->everyFiveMinutes();
Schedule::job(new ProcessUpdateSubscriptionUser())->everyFiveMinutes();
Schedule::job(new UpdateStatusInvoices())->daily();
