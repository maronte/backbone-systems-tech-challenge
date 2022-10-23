<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
