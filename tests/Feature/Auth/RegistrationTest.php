<?php

use App\Models\User;

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

it('should register a new user', function () {
    $response = post('api/auth/register', [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(201)
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

it('should not register a user with empty data', function () {
    $response = postJson('/api/auth/register', []);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});

it('should not register a user with invalid email', function() {
    $response = postJson('/api/auth/register', [
        'name' => 'John Doe',
        'email' => 'invalid-email',
        'password' => 'password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('should not register a user with password length under 6 character', function() {
    $response = postJson('/api/auth/register', [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => '123',
    ]);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

it('should not register a user with an existing email', function() {
    User::factory()->create(['email' => 'johndoe@example.com']);
    $response = postJson('/api/auth/register', [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

