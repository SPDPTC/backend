<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'data' => [
            'app' => 'SPDPTC',
            'version' => config('app.version'),
        ],
    ]);
});

Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login'])
            ->withoutMiddleware('auth:api');
        Route::post('register', [AuthController::class, 'register'])
            ->withoutMiddleware('auth:api');
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
    });

    Route::prefix('email')->group(function() {
        Route::get('/check', [EmailVerificationController::class, 'checkEmailVerificationStatus'])
            ->name('verification.notice');
        Route::get('/verify/{id}/{hash}', [EmailVerificationController::class, 'verifyEmail'])
            ->middleware('signed')
            ->name('verification.verify');
    });
});
