<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class AppInfoController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'data' => [
                'app' => 'SPDPTC',
                'version' => config('app.version'),
            ],
        ]);

    }
}
