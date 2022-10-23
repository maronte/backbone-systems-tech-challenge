<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *  title="FederalEntityResource",
 *  description="FederalEntity resource model",
 *  @OA\Xml(
 *      name="FederalEntityResource"
 *  ),
 *  @OA\Property(
 *      property="key",
 *      title="key",
 *      description="ID",
 *      example="9",
 *      type="number"
 *  ),
 *  @OA\Property(
 *      property="name",
 *      title="name",
 *      description="name of federal entity",
 *      example="CIUDAD DE MEXICO",
 *      type="string"
 *  ),
 *  @OA\Property(
 *      property="code",
 *      title="code",
 *      description="code of federal entity",
 *      example=null,
 *      type="null"
 *  )
 * )
 */
class FederalEntityResource extends JsonResource
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
            'key' => $this->id,
            'name' => $this->name,
            'code' => null,
        ];
    }
}
