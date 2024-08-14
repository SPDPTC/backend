<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Request $request) {
            return $request->is('api*') || $request->expectsJson();
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            if (
                $e instanceof NotFoundHttpException &&
                    ! str_starts_with($request->getPathInfo(), '/api')
            ) {
                return response()->json([
                    'message' => 'You need to add prefix `api` to your request.',
                ], 400);
            }

            return null;
        });
    })->create();
