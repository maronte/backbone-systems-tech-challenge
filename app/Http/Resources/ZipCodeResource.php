<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *  title="ZipCodeResource",
 *  description="Zip code resource model",
 *  @OA\Xml(
 *      name="ZipCodeResource"
 *  ),
 *  @OA\Property(
 *      property="zip_code",
 *      title="zip_code",
 *      description="ID",
 *      example="01090",
 *      type="string"
 *  ),
 *  @OA\Property(
 *      property="locality",
 *      title="locality",
 *      description="name of the locality of the zip code",
 *      example="CIUDAD DE MEXICO",
 *      type="string"
 *  ),
 *  @OA\Property(
 *      property="federal_entity",
 *      ref="#/components/schemas/FederalEntityResource"
 *  ),
 *  @OA\Property(
 *      property="settlements",
 *      @OA\Items(ref="#/components/schemas/SettlementResource"),
 *      type="array"
 *  ),
 *  @OA\Property(
 *      property="municipality",
 *      ref="#/components/schemas/MunicipalityResource"
 *  )
 * )
 */
class ZipCodeResource extends JsonResource
{
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
