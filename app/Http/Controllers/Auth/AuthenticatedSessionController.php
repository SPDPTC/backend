<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthenticatedSessionController
{
    public function store(LoginRequest $loginRequest): JsonResponse
    {
        if (! $token = auth('api')->attempt($loginRequest->validated())) {
            return response()->json([
                'message' => __('auth.failed'),
            ], 401);
        }

        return (new UserResource(auth('api')->user()))->additional([
            'token' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
            ],
        ])->response();
    }

    public function destroy(): JsonResponse
    {
        auth('api')->logout();

        return response()->json(['message' => __('auth.logout')]);
    }
}
