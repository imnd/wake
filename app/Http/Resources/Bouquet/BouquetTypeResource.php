<?php

namespace App\Http\Resources\Bouquet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'priority' => $this->priority,
            'image' => Storage::disk('public')->path("bouquet-types/{$this->image}"),
        ];
    }
}
