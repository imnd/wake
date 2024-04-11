<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     @OA\Xml(name="FullUserResource"),
 *     @OA\Property(property="id", type="int8", example="53"),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="avatar", type="string", example="http://b-ouquet.com/storage/avatars/3gpbiSkdpCbyIADiyb0wuopeSVnbT0gBCJfZi0GL_avatar.jpg"),
 *     @OA\Property(property="memorial", type="int8", example="35"),
 * )
 */
class FullUserResource extends ShortUserResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), []);
    }
}
