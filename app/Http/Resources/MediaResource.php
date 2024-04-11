<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Xml(name="MediaResource"),
 *     @OA\Property(property="uuid", type="string", example="dc9b0882-2f6f-4164-823d-ad2b0b8decb9"),
 *     @OA\Property(property="file_name", type="string", example="FLS4yzPIjW1I1PkC1uymrulxBUcrXyHl7xiDuEpm.jpg"),
 *     @OA\Property(property="mime_type", type="string", example="application/json"),
 * )
 */
class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'file_name' => $this->file_name,
            'mime_type' => $this->mime_type,
        ];
    }
}
