<?php

use App\Domain\Users\Actions\CreateUser;
use App\Support\Definitions\Roles;
use App\Support\Definitions\Status;
use Spatie\Permission\Models\Role;

test('it create the user', function () {
    Role::create(['name' => Roles::SUPER_ADMIN->name]);

    $this->artisan('app:create-user')
        ->expectsQuestion('What is your name?', 'gabriela')
        ->expectsQuestion('What is your email?', 'gabriela@test.com')
        ->expectsQuestion('Enter your password', '123456789')
        ->expectsOutput('User has been created!');

    $this->assertDatabaseHas('users', [
        'name' => 'gabriela',
        'email' => 'gabriela@test.com',
    ]);
});

test('it fail when user exist', function () {
    Role::create(['name' => Roles::SUPER_ADMIN->name]);

    $params = [
        'name' => 'admin',
        'email' => 'admin@placetogrow.com',
        'password' => '12345678',
        'status' => Status::ACTIVE->value,
        'role_id' => Roles::SUPER_ADMIN->value,
    ];
    CreateUser::execute($params);

    $this->artisan('app:create-user')
        ->expectsQuestion('What is your name?', 'gabriela')
        ->expectsQuestion('What is your email?', 'admin@placetogrow.com')
        ->expectsQuestion('Enter your password', '123456789')
        ->expectsOutput('Error to create user');
});
