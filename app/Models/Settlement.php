<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Settlement extends Model
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
            'name' => $data['d_asenta'],
            'key' => $data['id_asenta_cpcons'],
            // Use first letter because it will be calculated property.
            'zone_type' => substr($data['d_zona'], 0, 1),
            'settlement_type_id' => $data['c_tipo_asenta'],
            'zip_code_id' => $data['d_codigo'],
        ];
    }

    /**
     * Get the settlement type of the settlement.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne Settlement type relation.
     */
    public function settlementType(): BelongsTo
    {
        return $this->belongsTo(SettlementType::class);
    }

    /**
     * Interact with the settlement's type.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function zoneType(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === "U" ? "URBANO" : "RURAL"
        );
    }
}
