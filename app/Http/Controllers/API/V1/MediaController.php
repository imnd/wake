<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bouquets\UploadMediumRequest;
use App\Http\Requests\Memorials\UploadMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Bouquet;
use App\Models\Memorial;
use App\Services\BouquetService;
use App\Services\MemorialService;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Http\JsonResponse;

class MediaController extends Controller
{
    use ResponseTrait;

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
    public function destroy(Media $medium): JsonResponse
    {
        $medium->delete();

        return $this->responseNoContent();
    }

    /**
     * Upload memorial media
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/memorials/{MEMORIAL_ID}/media",
     *     summary="Upload media",
     *     description="Upload media to given memorial",
     *     operationId="upload-media",
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
    public function uploadMemorialMedia(
        Memorial $memorial,
        UploadMediaRequest $request,
        MemorialService $service,
    ): JsonResponse {
        $service->uploadMedia(
            model: $memorial,
            files: $request->file('files'),
        );

        return $this->responseOk([
            'message' => 'Successfully uploaded',
        ]);
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
    public function getMemorialMedia(
        Memorial $memorial,
        MemorialService $service,
    ): JsonResponse {
        return $this->responseOk(
            MediaResource::collection(
                $service->getMedia($memorial)
            )
        );
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
    public function getMemorialVideos(
        Memorial $memorial,
        MemorialService $service,
    ): JsonResponse {
        return $this->responseOk(
            MediaResource::collection(
                $service->getVideos($memorial)
            )
        );
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
    public function getMemorialImages(
        Memorial $memorial,
        MemorialService $service,
    ): JsonResponse {
        return $this->responseOk(
            MediaResource::collection(
                $service->getImages($memorial)
            )
        );
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
    public function destroyMemorialMedium(Media $medium): JsonResponse
    {
        if ($medium->model->user_id !== Auth::id()) {
            return $this->responseForbidden();
        }

        $medium->delete();

        return $this->responseNoContent();
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
    public function destroyMemorialMedia(Memorial $memorial): JsonResponse
    {
        foreach ($memorial->getAllMedia() as $medium) {
            $medium->delete();
        }

        return $this->responseNoContent();
    }

    /**
     * Upload medium
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/bouquets/{BOUQUET_ID}/media",
     *     summary="Upload medium",
     *     description="Upload medium to given bouquet",
     *     operationId="upload-medium",
     *     tags={"Bouquets"},
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
    public function uploadBouquetMedium(
        Bouquet $bouquet,
        UploadMediumRequest $request,
        BouquetService $service,
    ): JsonResponse {
        $service->uploadMedium(
            model: $bouquet,
            file: $request->file('file'),
        );

        return $this->responseOk(MediaResource::collection($bouquet->getMedia()));
    }
}
