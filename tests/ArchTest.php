<?php

arch()->preset()->php();
arch()->preset()->laravel()->ignoring([
    'App\Http\Controllers'
]);

arch('ensure has suffix controller')
    ->expect('App\Http\Controllers')
    ->classes()
    ->toHaveSuffix('Controller');

arch('strict types')
    ->expect('App')
    ->toUseStrictTypes()
    ->ignoring([
        'App\Http\Resources',
        'App\Http\Requests',
        'App\Providers'
    ]);

arch('ensure no extends')
    ->expect('App')
    ->classes()
    ->not->toBeAbstract();
