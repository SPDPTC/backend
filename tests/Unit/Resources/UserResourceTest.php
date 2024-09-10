<?php

use App\Http\Resources\UserResource;
use App\Models\User;

it('to array', function () {
    $user = User::factory()->create()->fresh();

    $userResource = new UserResource($user);

    expect($userResource->toArray(new \Illuminate\Http\Request()))
        ->toBe([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
});
