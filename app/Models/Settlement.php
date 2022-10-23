<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    /**
     * Maps the given data of a csv row of the zip file to
     * an associative array with model properties as keys.
     *
     * @param  array<string|int>  $data
     * @return array<string|int> $data
     */
    public static function mapCsvRowToModelAsArray(array $data)
    {
        return [
            'name' => $data['d_asenta'],
            'key' => $data['id_asenta_cpcons'],
            // Use first letter because it will be calculated property.
            'zone_type' => substr($data['d_zona'], 0, 1),
            'settlement_type_id' => $data['c_tipo_asenta'], 0, 1,
            'zip_code_id' => $data['d_codigo'],
        ];
    }

    use HasFactory;
}
