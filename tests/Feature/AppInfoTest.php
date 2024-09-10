<?php

use function Pest\Laravel\get;

it('should get information of SPDPTC version', function () {
    $response = get('api/');

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'app' => 'SPDPTC',
                'version' => '0.0.0',
            ],
        ]);
});
