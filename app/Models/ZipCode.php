<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZipCode extends Model
{
    use HasFactory;

    /**
     * Maps the given data of a csv row of the zip file to
     * an associative array with model properties as keys.
     *
     * @param  array<string|int>  $data
     * @param  int  $municipalityId
     * @return array<string|int> $data
     */
    public static function mapCsvRowToModelAsArray(array $data, int $municipalityId)
    {
        return [
            'id' => $data['d_codigo'],
            'locality' => $data['d_ciudad'],
            'federal_entity_id' => $data['c_estado'],
            'municipality_id' => $municipalityId,
        ];
    }
}
