<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="MunicipalityResource",
 *     description="Municipality resource model",
 *     @OA\Xml(
 *         name="MunicipalityResource"
 *     )
 * )
 */
class MunicipalityResource extends JsonResource
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
     *     example="ALVARO OBREGON",
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
            'key' => $this->key,
            'name' => $this->name,
        ];
    }
}
