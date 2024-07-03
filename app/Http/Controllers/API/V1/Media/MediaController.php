<?php

namespace App\Http\Controllers\API\V1\Media;

use App\Http\Requests\Media\UploadMediaRequest;
use App\Http\Requests\Media\UploadMediumRequest;
use App\Http\Resources\MediaResource;
use App\Services\MediaService;
use App\Traits\ResponseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

abstract class MediaController
{
    use ResponseTrait;

    protected function doUploadMedium(
        Model $model,
        UploadMediumRequest $request,
        MediaService $service,
    ): JsonResponse {
        $service->uploadMedium(
            model: $model,
            file: $request->file('file'),
        );

        return $this->responseOk(MediaResource::collection($model->getAllMedia()));
    }

    protected function doUploadMedia(
        Model $model,
        UploadMediaRequest $request,
        MediaService $service,
    ): JsonResponse {
        $service->uploadMedia(
            model: $model,
            files: $request->file('files'),
        );

        return $this->responseOk(MediaResource::collection($model->getAllMedia()));
    }

    protected function doGetMedia(Model $model): JsonResponse
    {
        return $this->responseOk(
            MediaResource::collection(
                $model->getAllMedia()
            )
        );
    }

    protected function doGetVideos(Model $model): JsonResponse
    {
        return $this->responseOk(
            MediaResource::collection(
                $model->getVideos()
            )
        );
    }

    protected function doGetImages(Model $model): JsonResponse
    {
        return $this->responseOk(
            MediaResource::collection(
                $model->getImages()
            )
        );
    }

    protected function doDestroyMedium(Media $medium): JsonResponse
    {
        if (!$this->checkAuthor($medium)) {
            return $this->responseForbidden();
        }

        $medium->delete();

        return $this->responseNoContent();
    }

    protected function doDestroyMedia(Model $model): JsonResponse
    {
        foreach ($model->getAllMedia() as $medium) {
            if ($this->checkAuthor($medium)) {
                $medium->delete();
            }
        }

        return $this->responseNoContent();
    }

    abstract protected function checkAuthor(Media $medium): bool;
}
