<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertTrue;

it('should send a confirmation email upon registration', function () {
    Event::fake();

    postJson('/api/auth/register', [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => 'password',
    ]);

    Event::assertListening(\Illuminate\Auth\Events\Registered::class, SendEmailVerificationNotification::class);
});

it('should verify the email succesfully', function () {
    /** @var App\Models\User $user */
    $user = User::factory()->unverified()->create();

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = actingAs($user)->get($verificationUrl);

    Event::assertDispatched(Verified::class);

    assertTrue($user->fresh()->hasVerifiedEmail());

    $response->assertStatus(200)
        ->assertExactJson(['message' => 'Email verified']);
});

it('should be able to resend the verification email', function () {
    Event::fake();

    $user = User::factory()->unverified()->create();

    $response = actingAs($user)->get('/api/email/verify');

    Event::assertListening(\Illuminate\Auth\Events\Registered::class, SendEmailVerificationNotification::class);

    $response->assertStatus(202)
        ->assertExactJson(['message' => 'Verification email has been resent to your email address'], 202);
});

it('should not be able to resend the verification email if the email is already verified', function () {
    /** @var App\Models\User $user */
    $user = User::factory()->create();

    $response = actingAs($user)->get('/api/email/verify');

    $response->assertStatus(400)
        ->assertExactJson(['message' => 'Email already verified']);
});
