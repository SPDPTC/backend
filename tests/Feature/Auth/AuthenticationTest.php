<?php

use App\Models\User;

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withHeaders;

it('should authenticate a user with valid credentials', function() {
    $user = User::factory()->create();

    $response = postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
            ],
            'token' => [
                'access_token',
                'token_type',
                'expires_in',
            ],
        ]);

    assertAuthenticated();
});

it('should log out the authenticated user', function() {
    $user = User::factory()->create();

    $response = postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $token = $response->json('token.access_token');

    $response = withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/auth/logout');

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Successfully logged out',
        ]);

    assertGuest();
});

it('should not authenticate a user with invalid credentials', function() {
    $user = User::factory()->create();

    $response = postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'invalid-password',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'message' => 'These credentials do not match our records.',
        ]);

    assertGuest();
});
