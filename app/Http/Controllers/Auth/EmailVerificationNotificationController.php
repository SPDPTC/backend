<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController
{
    /**
     * Send a new email verification notification.
     */
    public function __invoke(Request $request): JsonResponse
    {
        if($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email has been resent to your email address'], 202);
    }
}
