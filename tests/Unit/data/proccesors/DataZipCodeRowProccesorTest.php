<?php

use Data\Enums\Models;
use Data\Proccesors\DataZipCodeRowProccesor;

beforeEach(function () {
    $this->proccesor = new DataZipCodeRowProccesor();
});

test('should return data cleaned', function () {
    $expectedDataCleaned = [
        'd_codigo' => '01000',
        'd_asenta' => 'SAN ANGEL',
        'd_tipo_asenta' => 'Colonia',
        'D_mnpio' => 'ALVARO OBREGON',
        'd_estado' => 'CIUDAD DE MEXICO',
        'd_ciudad' => 'CIUDAD DE MEXICO',
        'd_CP' => '01001',
        'c_estado' => 9,
        'c_oficina' => '01001',
        'c_CP' => '',
        'c_tipo_asenta' => 9,
        'c_mnpio' => 10,
        'id_asenta_cpcons' => 1,
        'd_zona' => 'Urbano',
        'c_cve_ciudad' => '01',
    ];
    $dataToProcces = [
        'd_codigo' => '01000',
        'd_asenta' => 'San Ángel',
        'd_tipo_asenta' => 'Colonia',
        'D_mnpio' => 'Álvaro Obregón',
        'd_estado' => 'Ciudad de México',
        'd_ciudad' => 'Ciudad de México',
        'd_CP' => '01001',
        'c_estado' => '09',
        'c_oficina' => '01001',
        'c_CP' => '',
        'c_tipo_asenta' => '09',
        'c_mnpio' => '010',
        'id_asenta_cpcons' => '0001',
        'd_zona' => 'Urbano',
        'c_cve_ciudad' => '01',
    ];
    $rowProcessed = $this->proccesor->processRow($dataToProcces);
    expect($expectedDataCleaned)->toMatchArray($rowProcessed['data']);
});

test('should return models not proccesed and proccesed', function () {
    // All data is new
    $testData = [
        'd_codigo' => '01000',
        'd_asenta' => 'San Ángel',
        'd_tipo_asenta' => 'Colonia',
        'D_mnpio' => 'Álvaro Obregón',
        'd_estado' => 'Ciudad de México',
        'd_ciudad' => 'Ciudad de México',
        'd_CP' => '01001',
        'c_estado' => '09',
        'c_oficina' => '01001',
        'c_CP' => '',
        'c_tipo_asenta' => '09',
        'c_mnpio' => '010',
        'id_asenta_cpcons' => '0001',
        'd_zona' => 'Urbano',
        'c_cve_ciudad' => '01',
    ];
    $rowProcessed = $this->proccesor->processRow($testData);
    expect($rowProcessed['modelsToCreate'])->toHaveLength(4);
    expect($rowProcessed['modelsToCreate'])->toContain(
        Models::Municipality->name,
        Models::FederalEntity->name,
        Models::ZipCode->name,
        Models::SettlementType->name
    );

    // Just "municipality" is new
    $testData2 = [
        'd_codigo' => '01000',
        'd_asenta' => 'San Ángel',
        'd_tipo_asenta' => 'Colonia',
        'D_mnpio' => 'Álvaro Obregón',
        'd_estado' => 'Ciudad de México',
        'd_ciudad' => 'Ciudad de México',
        'd_CP' => '01001',
        'c_estado' => '10',
        'c_oficina' => '01001',
        'c_CP' => '',
        'c_tipo_asenta' => '09',
        'c_mnpio' => '010',
        'id_asenta_cpcons' => '0001',
        'd_zona' => 'Urbano',
        'c_cve_ciudad' => '01',
    ];
    $rowProcessed2 = $this->proccesor->processRow($testData2);
    expect($rowProcessed2['modelsToCreate'])->toHaveLength(1);
    expect($rowProcessed['modelsToCreate'])->toContain(
        Models::Municipality->name
    );

    // "munipality" and "federal_entity" is new
    $testData3 = [
        'd_codigo' => '01000',
        'd_asenta' => 'San Ángel',
        'd_tipo_asenta' => 'Colonia',
        'D_mnpio' => 'Álvaro Obregón',
        'd_estado' => 'Ciudad de México',
        'd_ciudad' => 'Ciudad de México',
        'd_CP' => '01001',
        'c_estado' => '11',
        'c_oficina' => '01001',
        'c_CP' => '',
        'c_tipo_asenta' => '09',
        'c_mnpio' => '009',
        'id_asenta_cpcons' => '0001',
        'd_zona' => 'Urbano',
        'c_cve_ciudad' => '01',
    ];
    $rowProcessed3 = $this->proccesor->processRow($testData3);
    expect($rowProcessed3['modelsToCreate'])->toHaveLength(2);
    expect($rowProcessed['modelsToCreate'])->toContain(
        Models::Municipality->name,
        Models::FederalEntity->name
    );

    // just "zip_code" is new
    $testData4 = [
        'd_codigo' => '01001',
        'd_asenta' => 'San Ángel',
        'd_tipo_asenta' => 'Colonia',
        'D_mnpio' => 'Álvaro Obregón',
        'd_estado' => 'Ciudad de México',
        'd_ciudad' => 'Ciudad de México',
        'd_CP' => '01001',
        'c_estado' => '11',
        'c_oficina' => '01001',
        'c_CP' => '',
        'c_tipo_asenta' => '09',
        'c_mnpio' => '009',
        'id_asenta_cpcons' => '0001',
        'd_zona' => 'Urbano',
        'c_cve_ciudad' => '01',
    ];
    $rowProcessed4 = $this->proccesor->processRow($testData4);
    expect($rowProcessed4['modelsToCreate'])->toHaveLength(1);
    expect($rowProcessed['modelsToCreate'])->toContain(
        Models::ZipCode->name
    );

    // just "settlement_type" is new
    $testData5 = [
        'd_codigo' => '01001',
        'd_asenta' => 'San Ángel',
        'd_tipo_asenta' => 'Colonia',
        'D_mnpio' => 'Álvaro Obregón',
        'd_estado' => 'Ciudad de México',
        'd_ciudad' => 'Ciudad de México',
        'd_CP' => '01001',
        'c_estado' => '11',
        'c_oficina' => '01001',
        'c_CP' => '',
        'c_tipo_asenta' => '01',
        'c_mnpio' => '009',
        'id_asenta_cpcons' => '0001',
        'd_zona' => 'Urbano',
        'c_cve_ciudad' => '01',
    ];
    $rowProcessed5 = $this->proccesor->processRow($testData5);
    expect($rowProcessed5['modelsToCreate'])->toHaveLength(1);
    expect($rowProcessed['modelsToCreate'])->toContain(
        Models::SettlementType->name
    );
});
