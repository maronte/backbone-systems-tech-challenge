<?php

namespace Data\Processors;

use App\Models\FederalEntity;
use App\Models\Municipality;
use App\Models\Settlement;
use App\Models\SettlementType;
use App\Models\ZipCode;
use Data\Enums\Models;
use Data\Enums\Tables;
use Illuminate\Support\Str;

// TODO: añadir formato de acentos tipo "\u00ed" y formatear nulos a string vacios
// para nombre
class DataZipCodeRowProcessor
{
    /**
     * Last state registered.
     *
     * @var string
     */
    protected $state = null;

    /**
     * Last municipality registered.
     *
     * @var string
     */
    protected $municipality = null;

    /**
     * Last municipality id registered.
     *
     * @var string
     */
    protected $municipalityId = 0;

    /**
     * Last zip code registered.
     *
     * @var string
     */
    protected $zipCode = null;

    /**
     * All possible values for settlement types.
     *
     * @var array<string>
     */
    protected $settlementTypes = [];

    /**
     * This function will clean the data to proccess it.
     *
     * This function will convert into integer data type the following
     * data of the row:
     * - c_estado
     * - c_mnpio
     * - id_asenta_cpcons
     * - c_tipo_asenta
     *
     * This function also will convert into upper case and removes
     * tildes for the following data of the row:
     * - d_estado
     * - D_mnpio
     * - d_ciudad
     * - d_asenta
     * - d_zona
     *
     * @param  array<string>  $row
     * @return array<string|int>
     */
    protected function cleanData(array $row): array
    {
        // Transform into integer.
        $row['c_estado'] = intval($row['c_estado']);
        $row['c_mnpio'] = intval($row['c_mnpio']);
        $row['id_asenta_cpcons'] = intval($row['id_asenta_cpcons']);
        $row['c_tipo_asenta'] = intval($row['c_tipo_asenta']);

        // Remove tildes (parsing the string to ascii) and transform into upper case.
        $row['d_estado'] = Str::upper(Str::ascii($row['d_estado']));
        $row['D_mnpio'] = Str::upper(Str::ascii($row['D_mnpio']));
        $row['d_ciudad'] = Str::upper(Str::ascii($row['d_ciudad']));
        $row['d_asenta'] = Str::upper(Str::ascii($row['d_asenta']));
        $row['d_zona'] = Str::upper(Str::ascii($row['d_zona']));

        // Just remove tildes
        $row['d_tipo_asenta'] = Str::ascii($row['d_tipo_asenta']);

        return $row;
    }

    /**
     * This function procces row of zip code csv file to know if
     * the given data belonging to a model was already processed
     * and to clean it as follows:
     *
     * From string to number:
     * - c_estado
     * - c_mnpio
     * - id_asenta_cpcons
     * - c_tipo_asenta
     *
     * From string to string upper case withouts accents:
     * - d_estado
     * - D_mnpio
     * - d_ciudad
     * - d_asenta
     * - d_zona
     *
     * @param  array<string>  $row
     * @return array This array has the keys:
     * - modelsToCreate: indicates what data of the row returned has to be proccesed
     * as new file. It has values of the enum Data\Enums\Models.
     * - data: Data to be proccesed already cleaned.
     */
    public function preProcessRow(array $row): array
    {
        $modelsToCreate = [];
        $row = $this->cleanData($row);

        $federalEntityHasBeenProcesed = $this->state === $row['c_estado'];
        if (! $federalEntityHasBeenProcesed) {
            array_push($modelsToCreate, Models::FederalEntity->name);
            $this->state = $row['c_estado'];
        }

        $municipalityHasBeenProcesed = $this->municipality === $row['c_mnpio'];
        if (! $municipalityHasBeenProcesed) {
            array_push($modelsToCreate, Models::Municipality->name);
            $this->municipality = $row['c_mnpio'];
            $this->municipalityId++;
        }

        $zipCodeHasBeenProcesed = $this->zipCode === $row['d_codigo'];
        if (! $zipCodeHasBeenProcesed) {
            array_push($modelsToCreate, Models::ZipCode->name);
            $this->zipCode = $row['d_codigo'];
        }

        $settlementTypeHasBeenProcesed = in_array($row['c_tipo_asenta'], $this->settlementTypes);
        if (! $settlementTypeHasBeenProcesed) {
            array_push($modelsToCreate, Models::SettlementType->name);
            array_push($this->settlementTypes, $row['c_tipo_asenta']);
        }

        return [
            'modelsToCreate' => $modelsToCreate,
            'data' => $row,
        ];
    }

    /**
     * This function procces row of zip code csv file already preprocessed
     * to transform it into corresponding given associative array model to
     * store it in an associative array with tables -> models.
     *
     * @param  array<array<string|int>>  $rowPreProcessed Array of data preprocessed.
     * @param  array<array<string|int>>  &$arrayOfModels Array to store models by table name.
     * @return void
     */
    public function processRow(array $preProcessedRow, array &$arrayOfModels): void
    {
        $modelsToCreate = $preProcessedRow['modelsToCreate'];
        $data = $preProcessedRow['data'];

        if (in_array(Models::FederalEntity->name, $modelsToCreate)) {
            $arrayModel = FederalEntity::mapCsvRowToModelAsArray($data);
            array_push($arrayOfModels[Tables::federal_entities->name], $arrayModel);
        }

        if (in_array(Models::Municipality->name, $modelsToCreate)) {
            $arrayModel = Municipality::mapCsvRowToModelAsArray($data);
            array_push($arrayOfModels[Tables::municipalities->name], $arrayModel);
        }

        if (in_array(Models::ZipCode->name, $modelsToCreate)) {
            $arrayModel = ZipCode::mapCsvRowToModelAsArray($data, $this->municipalityId);
            array_push($arrayOfModels[Tables::zip_codes->name], $arrayModel);
        }

        if (in_array(Models::SettlementType->name, $modelsToCreate)) {
            $arrayModel = SettlementType::mapCsvRowToModelAsArray($data);
            array_push($arrayOfModels[Tables::settlement_types->name], $arrayModel);
        }

        $arrayModel = Settlement::mapCsvRowToModelAsArray($data);
        array_push($arrayOfModels[Tables::settlements->name], $arrayModel);
    }
}
