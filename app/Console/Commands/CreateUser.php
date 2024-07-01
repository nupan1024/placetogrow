<?php

namespace App\Console\Commands;

use App\Domain\Users\Models\User;
use App\Support\Definitions\Roles;
use App\Support\Definitions\Status;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

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
        try {
            $result = User::insert([
                'name' => $this->ask('What is your name?'),
                'email' => $this->ask('What is your email?'),
                'password' => Hash::make($this->secret('Enter your password')),
                'status' => Status::ACTIVE->value,
                'role_id' => Roles::ADMIN->value,
                'email_verified_at' => now(),
            ]);

            if ($result) {
                $this->info('User has been created!');
            }
        } catch (\Exception $e) {
            $this->error('Error to create user');
            $this->error($e->getMessage());
        }
    }
}
