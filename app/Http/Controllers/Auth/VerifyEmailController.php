<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController
{
    public function __invoke(EmailVerificationRequest $emailVerificationRequest)
    {
        $emailVerificationRequest->fulfill();

        return response()->json(['message' => 'Email verified']);
    }
}
