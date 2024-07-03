<?php

namespace App\Http\Resources\Memorial;

use App\Http\Resources\User\ShortUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Xml(name="ShortMemorialResource"),
 *     @OA\Property(property="id", type="int8", example="53"),
 *     @OA\Property(property="uuid", type="uuid", example="f9b33f0e-53d2-4cb8-bb2d-702fd9bb0c68"),
 *     @OA\Property(property="title", type="string", example="Lorem ipsum"),
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="middle_name", type="string", example="Fitzgerald"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="place_of_birth", type="string", example="New York City"),
 *     @OA\Property(property="place_of_death", type="string", example="Concord"),
 *     @OA\Property(property="day_of_birth", type="string", example="2012-12-12T00:00:00.000000Z"),
 *     @OA\Property(property="day_of_death", type="string", example="2012-12-13T00:00:00.000000Z"),
 *     @OA\Property(property="text", type="string", example="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vel libero leo."),
 *     @OA\Property(property="default", type="boolean", example="true"),
 *     @OA\Property(property="status", type="string", example="paid"),
 *     @OA\Property(property="avatar", type="string", example="http://b-ouquet/storage/avatars/FLS4yzPIjW1I1PkC1uymrulxBUcrXyHl7xiDuEpm_avatar.jpg"),
 *     @OA\Property(
 *          property="user",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/ShortUserResource")
 *     ),
 * )
 */
class ShortMemorialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'place_of_birth' => $this->place_of_birth,
            'place_of_death' => $this->place_of_death,
            'day_of_birth' => $this->day_of_birth,
            'day_of_death' => $this->day_of_death,
            'text' => $this->text,
            'default' => $this->default,
            'status' => $this->status,
            'avatar' => $this->avatar,
            'user' => new ShortUserResource($this->author),
        ];
    }
}
