<?php

namespace App\Jobs;

use App\Domain\SubscriptionUser\Models\SubscriptionUser;
use App\Support\Services\Mail\SubscriptionEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
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

    private string $action;
    private Model|Collection $model;
    private array $emails;
    private array $params;
    public function __construct($action, $model, $emails, $params = [])
    {
        $this->action = $action;
        $this->model = $model;
        $this->emails = $emails;
        $this->params = $params;
    }

    public function handle(): void
    {
        switch ($this->action) {
            case 'updated_subscription':
                /* @var SubscriptionUser $model */
                foreach ($this->model as $model) {
                    Mail::to($model->user->email)->send(new SubscriptionEmail($model, $this->params));
                }
                break;
            default:
                break;
        }
    }
}
