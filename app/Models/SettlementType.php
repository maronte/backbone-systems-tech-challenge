<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettlementType extends Model
{
    use HasFactory;

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
            'id' => $data['c_tipo_asenta'],
            'name' => $data['d_tipo_asenta'],
        ];
    }
}
