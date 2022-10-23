<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *  title="SettlementResource",
 *  description="Settlement resource model",
 *  @OA\Xml(
 *      name="SettlementResource"
 *  ),
 *  @OA\Property(
 *      property="key",
 *      title="key",
 *      description="ID",
 *      example="25",
 *      type="number"
 *  ),
 *  @OA\Property(
 *      property="name",
 *      title="name",
 *      description="name of settlement",
 *      example="LA OTRA BANDA",
 *      type="string"
 *  ),
 *  @OA\Property(
 *     property="zone_type",
 *     title="zone_type",
 *     description="name of zone type of settlement",
 *     example="URBANO",
 *     type="string"
 *  ),
 *  @OA\Property(
 *      property="settlement_type",
 *      ref="#/components/schemas/SettlementTypeResource"
 *  )
 * )
 */
class SettlementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'key' => $this->key,
            'name' => $this->name,
            'zone_type' => $this->zone_type,
            'settlement_type' => new SettlementTypeResource($this->settlementType),
        ];
    }
}
