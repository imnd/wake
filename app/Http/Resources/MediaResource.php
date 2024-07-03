<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Xml(name="MediaResource"),
 *     @OA\Property(property="id", type="integer", example="9"),
 *     @OA\Property(property="uuid", type="string", example="dc9b0882-2f6f-4164-823d-ad2b0b8decb9"),
 *     @OA\Property(property="file_name", type="string", example="FLS4yzPIjW1I1PkC1uymrulxBUcrXyHl7xiDuEpm.jpg"),
 *     @OA\Property(property="collection_name", type="string", example="video-preview"),
 *     @OA\Property(property="mime_type", type="string", example="application/json"),
 * )
 */
class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $collectionName = $this->collection_name;

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'file_url' => $this->getUrl(),
            'preview_url' => $this->getUrl('image-' . substr($collectionName, strpos($collectionName, '-') + 1)),
            'collection_name' => $collectionName,
            'mime_type' => $this->mime_type,
        ];
    }
}
