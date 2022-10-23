<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="SettlementTypeResource",
 *     description="Settlement type resource model",
 *     @OA\Xml(
 *         name="SettlementTypeResource"
 *     )
 * )
 */
class SettlementTypeResource extends JsonResource
{
    /**
     * @OA\Property(
     *     title="name",
     *     description="name of settlement type",
     *     example="Barrio",
     *     type="string"
     * )
     */
    private $name;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            // Pase this line to javascript utf-8 format because it can have accents
            'name' => trim(json_encode($this->name), '"'),
        ];
    }
}
