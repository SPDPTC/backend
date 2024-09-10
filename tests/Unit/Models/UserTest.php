<?php

use App\Models\User;

test('to array', function () {
    $user = User::factory()->create()->fresh();

    expect(array_keys($user->toArray()))
        ->toBe([
            'id',
            'name',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at',
        ]);
});

test('jwt identifier', function () {
    $user = User::factory()->create();

    expect($user->getJWTIdentifier())->toBe($user->id);
});

test('jwt custom claims', function () {
    $user = User::factory()->create();

    expect($user->getJWTCustomClaims())->toBe([]);
});
