<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\UploadAvatarRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Http\Resources\User\FullUserResource;
use App\Models\User;
use App\Services\MediaService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(title="API", version="1.0.0")
 */
class UsersController extends Controller
{
    use ResponseTrait;

    /**
     * Get short user details
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/api/v1/users/{ID}",
     *     path="/api/v1/users/me",
     *     summary="Get short user details",
     *     description="Get short user details",
     *     operationId="show",
     *     tags={"Users"},
     *
     *     @OA\SecurityScheme(
     *         securityScheme="Bearer",
     *         type="apiKey",
     *         name="Authorization",
     *         in="header"
     *     ),
     *
     *     @OA\Parameter(
     *         in="path",
     *         name="ID",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         ref="#/components/schemas/FullUserResource"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * )
     */
    public function shortDetails(?User $user = null): JsonResponse
    {
        $user = $user ?? Auth::user();

        return $this->responseOk(new FullUserResource($user));
    }

    /**
     * Update user
     *
     * @return JsonResponse
     * @OA\Put(
     *     path="/api/v1/users/{ID}",
     *     summary="Update user",
     *     description="Update user",
     *     operationId="update-user",
     *     tags={"Users"},
     *
     *     @OA\SecurityScheme(
     *         securityScheme="Bearer",
     *         type="apiKey",
     *         name="Authorization",
     *         in="header"
     *     ),
     *
     *     @OA\Parameter(
     *         in="path",
     *         name="ID",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="name",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="email",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="password",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access Denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     ),
     * )
     */
    public function update(User $user, UpdateRequest $request): JsonResponse
    {
        $data = $request->all(['name', 'email']);
        if ($password = $request->password) {
            $data['password'] = Hash::make($password);
        }

        $user->update($data);

        return $this->responseNoContent();
    }

    /**
     * Disable user
     *
     * @return JsonResponse
     * @OA\Patch(
     *     path="/api/v1/users/{ID}",
     *     summary="Disable user",
     *     description="Disable user",
     *     operationId="destroy",
     *     tags={"Users"},
     *
     *     @OA\SecurityScheme(
     *         securityScheme="Bearer",
     *         type="apiKey",
     *         name="Authorization",
     *         in="header"
     *     ),
     *
     *     @OA\Parameter(
     *         in="path",
     *         name="ID",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     ),
     * )
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return $this->responseNoContent();
    }

    /**
     * Upload user avatar
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/users/{ID}/avatar",
     *     summary="Upload user avatar",
     *     description="Upload user avatar",
     *     operationId="upload-user-avatar",
     *     tags={"Users"},
     *
     *     @OA\SecurityScheme(
     *         securityScheme="Bearer",
     *         type="apiKey",
     *         name="Authorization",
     *         in="header"
     *     ),
     *
     *     @OA\Parameter(
     *         in="path",
     *         name="ID",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="file",
     *         required=true,
     *         @OA\Schema(
     *           type="file"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable entity"
     *     ),
     * )
     */
    public function uploadAvatar(
        ?User $user,
        UploadAvatarRequest $request,
        MediaService $service,
    ): JsonResponse {
        $user = $user ?? Auth::user();

        $service->uploadAvatar(
            file: $request->file('file'),
            model: $user,
        );

        return $this->responseNoContent();
    }
}
