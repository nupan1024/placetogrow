<?php

namespace App\Support\Services\Mail\User;

use App\Domain\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreatedEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly User $user)
    {
    }

    public function build(): self
    {
        return $this->subject('Tu usuario ha sido creado')
            ->view('emails.users.userCreated')
            ->with([
                'user' => $this->user,
            ]);
    }
}
