<?php

declare(strict_types=1);

use App\Http\Controllers\AppInfoController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::get('/', AppInfoController::class);

Route::middleware('auth:api')->group(function (): void {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthenticatedSessionController::class, 'store'])
            ->withoutMiddleware('auth:api');
        Route::post('register', RegisteredUserController::class)
            ->withoutMiddleware('auth:api');
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
    });

    Route::prefix('email')->group(function(): void {
        Route::get('/verify/{id}/{hash}', VerifyEmailController::class)
            ->middleware('signed')
            ->name('verification.verify');
        Route::get('/verify', EmailVerificationNotificationController::class)
            ->name('verification.send');
    });
});
