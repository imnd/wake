<?php

namespace App\Http\Resources\Bouquet;

use App\Http\Resources\MediaResource;
use App\Http\Resources\Memorial\ShortMemorialResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int    $id
 * @property bool   $anonymous
 * @property string $condolences
 * @property string $from
 * @property string $status
 * @property array  $memorial
 * @property string $created_at
 * @property mixed  $type
 * @property string $payment_method
 *
 * @OA\Schema(
 *     @OA\Xml(name="BouquetResource"),
 *     @OA\Property(property="id", type="int8", example="53"),
 *     @OA\Property(property="anonymous", type="boolean", example="1"),
 *     @OA\Property(property="condolences", type="string", example="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vel libero leo. Fusce vitae laoreet nisi. Cras faucibus ullamcorper sagittis. Donec ac magna a nunc faucibus congue sed non ante."),
 *     @OA\Property(property="from", type="string", example="John Doe"),
 *     @OA\Property(property="status", type="string", example="unpaid"),
 *     @OA\Property(property="type", type="object", ref="#/components/schemas/BouquetTypeResource"),
 *     @OA\Property(property="memorial", type="object", ref="#/components/schemas/ShortMemorialResource"),
 *     @OA\Property(property="media", type="object", ref="#/components/schemas/MediaResource"),
 *     @OA\Property(property="created", type="string", example="2024-04-25"),
 *     @OA\Property(property="payment_method", type="string", example="cashapp"),
 * )
 */
class BouquetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'anonymous' => $this->anonymous,
            'condolences' => $this->condolences,
            'from' => $this->from,
            'status' => $this->status,
            'type' => new BouquetTypeResource($this->type),
            'memorial' => new ShortMemorialResource($this->memorial),
            'media' => MediaResource::collection($this->getAllMedia()),
            'created' => $this->created_at,
            'payment_method' => $this->payment_method,
        ];
    }
}
