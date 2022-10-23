<?php

use Data\Readers\CsvFileReader;

beforeEach(function () {
    $this->reader = null;
    $this->fileNameToTest = 'zipcode-data-test.txt';
});

afterEach(function () {
    if (! is_null($this->reader)) {
        $this->reader->finishReader();
    }
});

test('should read a file as csv with generators', function () {
    $this->reader = new CsvFileReader(fileName: $this->fileNameToTest, linesPerIteration: 10);
    $rows = $this->reader->getRows();
    foreach ($rows as $row) {
        expect($row)->toBeArray();
        expect($row)->toHaveLength(10);
    }
});

test('should return an associative array with csv headers as keys', function () {
    $expectedKeys = [
        'd_codigo',
        'd_asenta',
        'd_tipo_asenta',
        'D_mnpio',
        'd_estado',
        'd_ciudad',
        'd_CP',
        'c_estado',
        'c_oficina',
        'c_CP',
        'c_tipo_asenta',
        'c_mnpio',
        'id_asenta_cpcons',
        'd_zona',
        'c_cve_ciudad',
    ];
    $this->reader = new CsvFileReader(fileName: $this->fileNameToTest, linesPerIteration: 1);
    $row = $this->reader->getRows()->current()[0];
    expect($row)->toBeArray();
    expect(array_keys($row))->toMatchArray($expectedKeys);
});
