<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Memorial\ShortMemorialResource;
use App\Traits\MediaTrait;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     @OA\Xml(name="FullUserResource"),
 *     @OA\Property(property="id", type="int8", example="53"),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="avatar", type="string", example="http://b-ouquet.com/storage/avatars/3gpbiSkdpCbyIADiyb0wuopeSVnbT0gBCJfZi0GL_avatar.jpg"),
 *     @OA\Property(property="memorial", type="int8", example="35"),
 *     @OA\Property(
 *          property="memorials",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/ShortMemorialResource")
 *     )
 * )
 */
class FullUserResource extends ShortUserResource
{
    use MediaTrait;

    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'memorial' => $this->memorial()?->id,
            'memorials' => ShortMemorialResource::collection($this->viewedMemorials),
        ]);
    }
}
