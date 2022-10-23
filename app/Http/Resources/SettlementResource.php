<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="SettlementResource",
 *     description="Settlement resource model",
 *     @OA\Xml(
 *         name="SettlementResource"
 *     )
 * )
 */
class SettlementResource extends JsonResource
{
    /**
     * @OA\Property(
     *     title="key",
     *     description="ID",
     *     example="25",
     *     type="number"
     * )
     */
    private $key;

    /**
     * @OA\Property(
     *     title="name",
     *     description="name of settlement",
     *     example="LA OTRA BANDA",
     *     type="string"
     * )
     */
    private $name;

    /**
     * @OA\Property(
     *     title="name",
     *     description="name of zone type of settlement",
     *     example="URBANO",
     *     type="string"
     * )
     */
    private $zone_type;

    /**
     * @OA\Property(
     *  ref="#/components/schemas/SettlementTypeResource"
     * )
     */
    private $settlement_type;

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
