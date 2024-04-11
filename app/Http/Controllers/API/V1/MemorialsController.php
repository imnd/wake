<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Memorials\{
    ChangeStatusRequest,
    CreateRequest,
    UpdateRequest,};
use App\Http\Requests\UploadAvatarRequest;
use App\Http\Resources\Memorial\FullMemorialResource;
use App\Http\Resources\Memorial\ShortMemorialResource;
use App\Models\Memorial;
use App\Models\User;
use App\Services\MemorialService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MemorialsController extends Controller
{
    use ResponseTrait;

    /**
     * Memorials
     * Returns memorials related to user
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/api/v1/users/{USER_ID}/memorials",
     *     summary="Get user memorials",
     *     description="Get user memorials",
     *     operationId="get-memorials",
     *     tags={"Memorials"},
     *
     *     @OA\Parameter(
     *         in="path",
     *         name="USER_ID",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ShortMemorialResource")
     *         ),
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
    public function index(MemorialService $service, User $user): ?JsonResponse
    {
        $memorials = $service->getList($user->id, Auth::id());

        return $this->responseOk(ShortMemorialResource::collection($memorials));
    }

    /**
     * Get memorial info
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/api/v1/memorials/{MEMORIAL_ID}",
     *     summary="Get memorial info",
     *     description="Get memorial info",
     *     operationId="get-memorial",
     *     tags={"Memorials"},
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
     *         name="MEMORIAL_ID",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/ShortMemorialResource"),
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
    public function show(Memorial $memorial): ?JsonResponse
    {
        if ($memorial->user_id !== Auth::id() && !$memorial->isPublished()) {
            return $this->responseForbidden();
        }

        return $this->responseOk(new FullMemorialResource($memorial));
    }

    /**
     * Create memorial
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/memorials",
     *     summary="Create memorial",
     *     description="Create memorial",
     *     operationId="create-memorial",
     *     tags={"Memorials"},
     *
     *     @OA\SecurityScheme(
     *         securityScheme="Bearer",
     *         type="apiKey",
     *         name="Authorization",
     *         in="header"
     *     ),
     *
     *     @OA\Parameter(
     *         in="query",
     *         name="default",
     *         required=false,
     *         @OA\Schema(
     *           type="boolean"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Created"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable entity"
     *     )
     * )
     */
    public function create(
        CreateRequest $request,
        MemorialService $service,
    ): JsonResponse {
        $memorial = $service->create($request, Auth::id());

        return $this->responseCreated(new ShortMemorialResource($memorial));
    }

    /**
     * Update memorial
     *
     * @return JsonResponse
     * @OA\Put(
     *     path="/api/v1/memorials/{MEMORIAL_ID}",
     *     summary="Update memorial",
     *     description="Update memorial",
     *     operationId="update-memorial",
     *     tags={"Memorials"},
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
     *         name="MEMORIAL_ID",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/ShortMemorialResource"),
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
     *     )
     * )
     */
    public function update(
        UpdateRequest $request,
        MemorialService $service,
        Memorial $memorial,
    ) {
        $service->update($request, $memorial);

        return $this->responseOk(new FullMemorialResource($memorial));
    }

    /**
     * Change memorial status
     *
     * @return JsonResponse
     * @OA\Patch(
     *     path="/api/v1/memorials/{MEMORIAL_ID}",
     *     summary="Change memorial status",
     *     description="Change memorial status",
     *     operationId="change-memorial-status",
     *     tags={"Memorials"},
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
     *         name="MEMORIAL_ID",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="status",
     *         required=true,
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
     *     )
     * )
     */
    public function changeStatus(
        Memorial $memorial,
        MemorialService $service,
        ChangeStatusRequest $request,
    ): JsonResponse {
        $service->changeStatus($memorial, $request->status);

        return $this->responseNoContent();
    }

    /**
     * Delete memorial
     *
     * @return JsonResponse
     * @OA\Delete(
     *     path="/api/v1/memorial/{MEMORIAL_ID}",
     *     summary="Delete memorial",
     *     description="Delete all memorial",
     *     operationId="destroy-memorial",
     *     tags={"Memorials"},
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
     *         name="MEMORIAL_ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
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
     *     )
     * )
     */
    public function destroy(
        Memorial $memorial,
        MemorialService $service,
    ): JsonResponse {
        $service->changeStatus($memorial, Memorial::STATUS_DELETED);

        return $this->responseNoContent();
    }

    /**
     * Upload memorial avatar
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/memorials/{MEMORIAL_ID}/avatar",
     *     summary="Upload memorial avatar",
     *     description="Upload memorial avatar",
     *     operationId="upload-memorial-avatar",
     *     tags={"Memorials"},
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
     *         name="MEMORIAL_ID",
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
     *         response=403,
     *         description="Access Denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable entity"
     *     )
     * )
     */
    public function uploadAvatar(
        Memorial $memorial,
        UploadAvatarRequest $request,
        MemorialService $service,
    ): JsonResponse {
        $service->uploadAvatar(
            file: $request->file('file'),
            model: $memorial,
        );

        return $this->responseNoContent();
    }
}
