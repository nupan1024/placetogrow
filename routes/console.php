<?php

use App\Jobs\UpdateStatusPayments;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new UpdateStatusPayments())->everyMinute();
