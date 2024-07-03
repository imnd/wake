<?php

namespace App\Http\Resources\Memorial;

use App\Http\Resources\User\FullUserResource;
use App\Http\Resources\User\ShortUserResource;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     @OA\Xml(name="FullMemorialResource"),
 *     @OA\Property(property="id", type="int8", example="53"),
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
 *     @OA\Property(
 *          property="user",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/FullUserResource")
 *     ),
 *     @OA\Property(property="gender", type="string", example="male"),
 *     @OA\Property(property="goal_sum", type="number", example="0"),
 *     @OA\Property(property="avatar", type="string", example="http://b-ouquet/storage/avatars/FLS4yzPIjW1I1PkC1uymrulxBUcrXyHl7xiDuEpm_avatar.jpg"),
 *     @OA\Property(
 *          property="viewers",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/ShortUserResource")
 *     ),
 * )
 */
class FullMemorialResource extends ShortMemorialResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'gender' => $this->gender,
            'goal_sum' => $this->goal_sum,
            'media' => array_slice($this->getImagePreviews()->toArray(), 0, 3),
            'viewers' => ShortUserResource::collection($this->viewers),
        ]);
    }
}
