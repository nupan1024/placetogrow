<?php

namespace App\Console\Commands;

use App\Support\Definitions\Roles;
use App\Support\Definitions\Status;
use Illuminate\Console\Command;
use App\Domain\Users\Actions\CreateUser as ActionCreateUser;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->ask('What is your name?');
        $email = $this->ask('What is your email?');
        $password = $this->ask('Enter your password');

        $params = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'status' => Status::ACTIVE->value,
            'role_id' => Roles::ADMIN->value,
        ];

        $result = ActionCreateUser::execute($params);
        (!$result) ? $this->error('Error to create user') : $this->info('User has been created!');
    }

}
