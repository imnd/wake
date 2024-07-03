<?php

namespace App\Http\Controllers\API\V1\Media;

use App\Http\Requests\Media\UploadMediaRequest;
use App\Http\Requests\Media\UploadMediumRequest;
use App\Models\Memorial;
use App\Services\MediaService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MemorialMediaController extends MediaController
{
    use ResponseTrait;

    /**
     * Upload memorial medium
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/memorials/{MEMORIAL_ID}/medium",
     *     summary="Upload medium",
     *     description="Upload medium to given memorial",
     *     operationId="upload-memorial-medium",
     *     tags={"Memorials media"},
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
     *           type="array[file]"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Successfully uploaded",
     *              ),
     *         )
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
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable entity"
     *     )
     * )
     */
    public function uploadMedium(
        Memorial $memorial,
        UploadMediumRequest $request,
        MediaService $service,
    ): JsonResponse {
        return $this->doUploadMedium($memorial, $request, $service);
    }

    /**
     * Upload memorial media
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/memorials/{MEMORIAL_ID}/media",
     *     summary="Upload media",
     *     description="Upload media to given memorial",
     *     operationId="upload-memorial-media",
     *     tags={"Memorials media"},
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
     *         name="files",
     *         required=true,
     *         @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  type="file",
     *              ),
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Successfully uploaded",
     *              ),
     *         )
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
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable entity"
     *     )
     * )
     */
    public function uploadMedia(
        Memorial $memorial,
        UploadMediaRequest $request,
        MediaService $service,
    ): JsonResponse {
        return $this->doUploadMedia($memorial, $request, $service);
    }

    /**
     * Get all memorial media
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/api/v1/memorials/{MEMORIAL_ID}/media",
     *     summary="Get all memorial media",
     *     description="Get all memorial media",
     *     operationId="get-memorial-media",
     *     tags={"Memorials media"},
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
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable entity"
     *     )
     * )
     */
    public function getMedia(Memorial $memorial): JsonResponse
    {
        return $this->doGetMedia($memorial);
    }

    /**
     * Get memorial videos
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/api/v1/memorials/{MEMORIAL_ID}/video",
     *     summary="Get memorial videos",
     *     description="Get memorial videos",
     *     operationId="get-memorial-videos",
     *     tags={"Memorials media"},
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
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable entity"
     *     )
     * )
     */
    public function getVideos(Memorial $memorial): JsonResponse
    {
        return $this->doGetVideos($memorial);
    }

    /**
     * Get memorial images
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/api/v1/memorials/{MEMORIAL_ID}/images",
     *     summary="Get memorial images",
     *     description="Get memorial images",
     *     operationId="get-memorial-images",
     *     tags={"Memorials media"},
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
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable entity"
     *     )
     * )
     */
    public function getImages(Memorial $memorial): JsonResponse
    {
        return $this->doGetImages($memorial);
    }

    /**
     * Delete memorial medium
     *
     * @return JsonResponse
     * @OA\Delete(
     *     path="/api/v1/memorials/media/{MEDIUM_ID}",
     *     summary="Delete medium",
     *     description="Delete medium",
     *     operationId="destroy-memorial-medium",
     *     tags={"Memorials media"},
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
     *         name="MEDIUM_ID",
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
     *         response=403,
     *         description="Access Denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * )
     */
    public function destroyMedium(Media $medium): JsonResponse
    {
        return $this->doDestroyMedium($medium);
    }

    /**
     * Delete all memorial media
     *
     * @return JsonResponse
     * @OA\Delete(
     *     path="/api/v1/memorial/{MEMORIAL_ID}/media",
     *     summary="Delete memorial media",
     *     description="Delete all memorial media",
     *     operationId="destroy-memorial-media",
     *     tags={"Memorials media"},
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
    public function destroyMedia(Memorial $memorial): JsonResponse
    {
        return $this->doDestroyMedia($memorial);
    }

    protected function checkAuthor(Media $medium): bool
    {
        return $medium->model->user_id === Auth::id();
    }
}
