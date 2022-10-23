<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="FederalEntityResource",
 *     description="Federal entity resource model",
 *     @OA\Xml(
 *         name="ZipCodeResource"
 *     )
 * )
 */
class FederalEntityResource extends JsonResource
{
    /**
     * @OA\Property(
     *     title="key",
     *     description="ID",
     *     example="9",
     *     type="number"
     * )
     */
    private $key;

    /**
     * @OA\Property(
     *     title="name",
     *     description="name of federal entity",
     *     example="CIUDAD DE MEXICO",
     *     type="string"
     * )
     */
    private $name;

    /**
     * @OA\Property(
     *     title="code",
     *     description="code of federal entity",
     *     example=null,
     *     type="null"
     * )
     */
    private $code;

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
