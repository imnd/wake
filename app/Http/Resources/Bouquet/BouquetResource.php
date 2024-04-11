<?php

namespace App\Http\Resources\Bouquet;

use App\Http\Resources\Memorial\ShortMemorialResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Xml(name="BouquetResource"),
 *     @OA\Property(property="id", type="int8", example="53"),
 *     @OA\Property(property="condolences", type="string", example="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vel libero leo. Fusce vitae laoreet nisi. Cras faucibus ullamcorper sagittis. Donec ac magna a nunc faucibus congue sed non ante."),
 *     @OA\Property(property="from", type="string", example="John Doe"),
 *     @OA\Property(property="status", type="string", example="unpaid"),
 *     @OA\Property(property="memorial", type="object", ref="#/components/schemas/ShortMemorialResource"),
 *     @OA\Property(property="type", type="object", ref="#/components/schemas/BouquetTypeResource"),
 * )
 */
class BouquetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'condolences' => $this->condolences,
            'from' => $this->from,
            'status' => $this->status,
            'memorial' => new ShortMemorialResource($this->memorial),
            'type' => new BouquetTypeResource($this->memorial),
        ];
    }
}
