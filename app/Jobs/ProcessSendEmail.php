<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessSendEmail implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     *  @template T of \Illuminate\Mail\Mailable
     * @param class-string<T> $mail
     */
    public function __construct(private readonly string $mail, private readonly Collection $users, private readonly array $params = [])
    {
    }

    public function handle(): void
    {
        foreach ($this->users as $user) {
            Mail::to($user->email)->send(new $this->mail($user, $this->params));
        }
    }
}
