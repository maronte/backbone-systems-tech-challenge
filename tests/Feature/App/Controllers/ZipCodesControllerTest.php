<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

uses(TestCase::class, DatabaseMigrations::class);

beforeEach(function () {
    $this->seed();
});

it('has zip-codes endpoint', function () {
    $expectedResponse = [
        'zip_code' => '01090',
        'locality' => 'CIUDAD DE MEXICO',
        'federal_entity' => [
            'key' => 9,
            'name' => 'CIUDAD DE MEXICO',
            'code' => null,
        ],
        'settlements' => [
            [
                'key' => 25,
                'name' => 'LA OTRA BANDA',
                'zone_type' => 'URBANO',
                'settlement_type' => [
                    'name' => 'Barrio',
                ],
            ],
            [
                'key' => 26,
                'name' => 'LORETO',
                'zone_type' => 'URBANO',
                'settlement_type' => [
                    'name' => 'Barrio',
                ],
            ],
            [
                'key' => 28,
                'name' => 'TIZAPAN',
                'zone_type' => 'URBANO',
                'settlement_type' => [
                    'name' => 'Pueblo',
                ],
            ],
        ],
        'municipality' => [
            'key' => 10,
            'name' => 'ALVARO OBREGON',
        ],
    ];
    $response = $this->get('/api/zip-codes/01090');
    $response->assertStatus(200)->assertJson($expectedResponse);
});
