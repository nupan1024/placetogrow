<?php

namespace App\Support\Observers;

use App\Domain\Users\Models\User;
use App\Jobs\ProcessSendEmail;
use App\Support\Services\Mail\User\UserCreatedEmail;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class UserObserver implements ShouldHandleEventsAfterCommit
{
    public function created(User $user): void
    {
        dispatch(new ProcessSendEmail(UserCreatedEmail::class, $user));
    }

}
