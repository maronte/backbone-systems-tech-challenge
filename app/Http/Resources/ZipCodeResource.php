<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="ZipCodeResource",
 *     description="Zip code resource model",
 *     @OA\Xml(
 *         name="ZipCodeResource"
 *     )
 * )
 */
class ZipCodeResource extends JsonResource
{
    /**
     * @OA\Property(
     *     title="zip_code",
     *     description="ID",
     *     example="01090",
     *     type="string"
     * )
     */
    private $zip_code;

    /**
     * @OA\Property(
     *     title="locality",
     *     description="locality name of zip code",
     *     example="CIUDAD DE MEXICO",
     *     type="string"
     * )
     */
    private $locality;

    /**
     * @OA\Property(ref="#/components/schemas/FederalEntityResource")
     */
    private $federal_entity;

    /**
     * @OA\Property(
     *     @OA\Items(ref="#/components/schemas/SettlementResource"),
     *     type="array"
     * )
     */
    private $settlements;

    /**
     * @OA\Property(ref="#/components/schemas/MunicipalityResource")
     */
    private $municipality;

    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'zip_code' => $this->id,
            'locality' => $this->locality,
            'federal_entity' => new FederalEntityResource($this->federalEntity),
            'municipality' => new MunicipalityResource($this->municipality),
            'settlements' => new SettlementCollection($this->settlements),
        ];
    }
}
