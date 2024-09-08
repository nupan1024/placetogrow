<?php

use App\Domain\Users\Actions\GetUserByEmail;
use App\Domain\Users\Models\User;

test('get user by email', function () {
    $email = fake()->email();
    User::factory()->create([
        'email' => $email
    ]);

    expect(GetUserByEmail::execute(['email' => $email]))->toBeInstanceOf(User::class);
});
