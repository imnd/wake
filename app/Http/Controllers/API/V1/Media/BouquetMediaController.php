<?php

namespace App\Http\Controllers\API\V1\Media;

use App\Http\Requests\Media\UploadMediaRequest;
use App\Http\Requests\Media\UploadMediumRequest;
use App\Models\Bouquet;
use App\Services\MediaService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BouquetMediaController extends MediaController
{
    use ResponseTrait;

    const LIMITAT_MESSAGE = 'Bouquet can have only only 3 media';

    /**
     * Upload bouquet media item
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/bouquets/{BOUQUET_ID}/medium",
     *     summary="Upload bouquet media item",
     *     description="Upload media item to given bouquet",
     *     operationId="upload-bouquet-media-item",
     *     tags={"Bouquets media"},
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
     *         name="BOUQUET_ID",
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
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/MediaResource"
     *         ),
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
        Bouquet $bouquet,
        UploadMediumRequest $request,
        MediaService $service,
    ): JsonResponse {
        if ($bouquet->getAllMedia()->count() === Bouquet::MAX_MEDIA) {
            return $this->responseBadRequest(self::LIMITAT_MESSAGE);
        }

        return $this->doUploadMedium($bouquet, $request, $service);
    }

    /**
     * Upload bouquet media
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/bouquets/{BOUQUET_ID}/media",
     *     summary="Upload bouquet media",
     *     description="Upload media to given bouquet",
     *     operationId="upload-bouquet-media",
     *     tags={"Bouquets media"},
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
     *         name="BOUQUET_ID",
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
        Bouquet $bouquet,
        UploadMediaRequest $request,
        MediaService $service,
    ): JsonResponse {
        if (count($request->file('files')) + $bouquet->getAllMedia()->count() > Bouquet::MAX_MEDIA) {
            return $this->responseBadRequest(self::LIMITAT_MESSAGE);
        }

        return $this->doUploadMedia($bouquet, $request, $service);
    }

    /**
     * Get all bouquet media
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/api/v1/bouquets/{BOUQUET_ID}/media",
     *     summary="Get all bouquet media",
     *     description="Get all bouquet media",
     *     operationId="get-bouquet-media",
     *     tags={"Bouquets media"},
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
     *         name="BOUQUET_ID",
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
    public function getMedia(Bouquet $bouquet): JsonResponse
    {
        return $this->doGetMedia($bouquet);
    }

    /**
     * Get bouquet videos
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/api/v1/bouquets/{BOUQUET_ID}/video",
     *     summary="Get bouquet videos",
     *     description="Get bouquet videos",
     *     operationId="get-bouquet-videos",
     *     tags={"Bouquets media"},
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
     *         name="BOUQUET_ID",
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
    public function getVideos(Bouquet $bouquet): JsonResponse
    {
        return $this->doGetVideos($bouquet);
    }

    /**
     * Get bouquet images
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/api/v1/bouquets/{BOUQUET_ID}/images",
     *     summary="Get bouquet images",
     *     description="Get bouquet images",
     *     operationId="get-bouquet-images",
     *     tags={"Bouquets media"},
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
     *         name="BOUQUET_ID",
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
    public function getImages(Bouquet $bouquet): JsonResponse
    {
        return $this->doGetImages($bouquet);
    }

    /**
     * Delete medium
     *
     * @return JsonResponse
     * @OA\Delete(
     *     path="/api/v1/media/{MEDIA_ID}",
     *     summary="Delete medium",
     *     description="Delete medium",
     *     operationId="destroy-medium",
     *     tags={"Media"},
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
     *         name="MEDIA_ID",
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
        $medium->delete();

        return $this->responseNoContent();
    }

    /**
     * Delete all bouquet media
     *
     * @return JsonResponse
     * @OA\Delete(
     *     path="/api/v1/bouquet/{BOUQUET_ID}/media",
     *     summary="Delete bouquet media",
     *     description="Delete all bouquet media",
     *     operationId="destroy-bouquet-media",
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
     *         name="BOUQUET_ID",
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
    public function destroyMedia(Bouquet $bouquet): JsonResponse
    {
        return $this->doDestroyMedia($bouquet);
    }

    protected function checkAuthor(Media $medium): bool
    {
        $model = $medium->model;

        return $model->user_id === Auth::id() || $model->memorial->user_id === Auth::id();
    }
}
