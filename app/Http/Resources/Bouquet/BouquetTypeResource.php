<?php

namespace App\Http\Resources\Bouquet;

use App\Traits\MediaTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Xml(name="BouquetTypeResource"),
 *     @OA\Property(property="id", type="int8", example="53"),
 *     @OA\Property(property="name", type="string", example="White Lilies"),
 *     @OA\Property(property="price", type="string", example="15.0"),
 *     @OA\Property(property="image", type="string", example="/storage/app/public/bouquet-types/r-p.png"),
 *     @OA\Property(property="priority", type="string", example="1"),
 * )
 */
class BouquetTypeResource extends JsonResource
{
    use MediaTrait;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'priority' => $this->priority,
            'image' => $this->getDisk()->url("bouquet-types/{$this->image}"),
        ];
    }
}
