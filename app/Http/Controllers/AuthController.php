<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected function respondWithToken(string $token): JsonResponse
    {
        $expiresIn = JWTAuth::factory()->getTTL() * 60;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiresIn,
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (! $token = Auth::attempt($request->validated())) {
            return response()->json([
                'message' => __('auth.failed'),
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me(): JsonResponse
    {
        return response()->json([
            'message' => 'Success get user data',
            'data' => UserResource::make(Auth::user()),
        ]);
    }

    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json(['message' => __('auth.logout')]);
    }

    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(Auth::refresh());
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        event(new Registered($user));

        $token = Auth::login($user);

        return response()->json([
            'message' => __('auth.registered'),
            'data' => [
                'user' => UserResource::make($user),
                'token' => $this->respondWithToken($token)->getData(),
            ],
        ], 201);
    }
}
