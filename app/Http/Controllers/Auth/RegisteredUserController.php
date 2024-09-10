<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class RegisteredUserController
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        event(new Registered($user));

        /** @var string $token */
        $token = auth('api')->login($user);

        return (new UserResource(auth('api')->user()))->additional([
            'token' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
            ],
        ])->response();
    }
}
