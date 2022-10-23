<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ZipCode extends Model
{
    use HasFactory;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';
    
    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

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

    /**
     * Get the federal entity of the zip code.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne Federal entity relation.
     */
    public function federalEntity(): BelongsTo
    {
        return $this->belongsTo(FederalEntity::class);
    }

    /**
     * Get the municipality of the zip code.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne Municipality relation.
     */
    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    /**
     * Get the settlements of the zip code.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne Settlements entity relation.
     */
    public function settlements(): HasMany
    {
        return $this->hasMany(Settlement::class);
    }
}
