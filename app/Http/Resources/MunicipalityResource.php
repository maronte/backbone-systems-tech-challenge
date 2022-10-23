<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *  title="MunicipalityResource",
 *  description="Municipality resource model",
 *  @OA\Xml(
 *      name="MunicipalityResource"
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
 *      description="name of municipality",
 *      example="ALVARO OBREGON",
 *      type="string"
 *  )
 * )
 */
class MunicipalityResource extends JsonResource
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
        ];
    }
}
