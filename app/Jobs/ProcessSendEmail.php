<?php

namespace App\Jobs;

use App\Domain\Users\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
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
    public function __construct(private readonly string $mail, private readonly User $user, private readonly array $params = [])
    {
    }

    public function handle(): void
    {
        Mail::to($this->user->email)->send(new $this->mail($this->user, $this->params));
    }
}
