<?php

namespace App\Http\Resources\User;

use App\Traits\MediaTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Xml(name="ShortUserResource"),
 *     @OA\Property(property="id", type="int8", example="53"),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="avatar", type="string", example="http://b-ouquet.com/storage/avatars/3gpbiSkdpCbyIADiyb0wuopeSVnbT0gBCJfZi0GL_avatar.jpg"),
 * )
 */
class ShortUserResource extends JsonResource
{
    use MediaTrait;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->getDisk()->url($this->avatar),
        ];
    }
}
