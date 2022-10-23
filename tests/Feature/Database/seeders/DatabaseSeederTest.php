<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

uses(TestCase::class, DatabaseMigrations::class);

beforeEach(function () {
    $this->seed();
});

test('database should have all federal entities')->assertDatabaseHas('federal_entities', [
    'id' => [9, 32],
]);

test('database should have all municipalities')->assertDatabaseHas('municipalities', [
    'key' => [10, 29, 2],
]);

test('database should have all zip codes')->assertDatabaseHas('zip_codes', [
    'id' => [
        '01090',
        '01100',
        '01109',
        '01109',
        '01110',
        '01120',
        '02000',
        '02010',
        '02020',
        '99980',
        '99984',
        '99987',
        '99990',
        '99993',
        '99994',
        '99997',
        '99998',
    ],
]);

test('database should have all settlement types')->assertDatabaseCount('settlement_types', 5);

test('database should have all settlements')->assertDatabaseCount('settlements', 30);
