<?php

namespace App\Traits;

use App\Jobs\ResizeAvatar;
use App\Jobs\ResizeMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait MediaServiceTrait
{
    protected function putToStorage(string $path, UploadedFile $file): string
    {
        return Storage::disk(config('media-library.disk_name'))->put($path, $file);
    }

    protected function getStoragePath(string $path = ''): string
    {
        return Storage::disk(config('media-library.disk_name'))->path($path);
    }

    protected function getMediaUrl($path): string
    {
        return Storage::disk(config('media-library.disk_name'))->url($path);
    }

    public function uploadAvatar(UploadedFile $file, Model $model): void
    {
        $uploadPath = $this->putToStorage('avatars', $file);
        $hashName = $file->hashName();
        $dotPos = strpos($hashName, '.');
        ResizeAvatar::dispatch(
            model: $model,
            fileName: substr($hashName, 0, $dotPos) . '_avatar' . substr($hashName, $dotPos),
            filePath: $this->getStoragePath($uploadPath),
        );
    }

    public function uploadMedia(Model $model, array $files): void
    {
        foreach ($files as $file) {
            $this->uploadMedium($model, $file);
        }
    }

    public function uploadMedium(Model $model, UploadedFile $file): void
    {
        ResizeMedia::dispatch(
            model: $model,
            uploadPath: $this->putToStorage('bouquet', $file),
            mimeType: $file->getMimeType(),
        );
    }

    public function getMedia(Model $model)
    {
        return $model->getAllMedia();
    }

    public function getVideos(Model $model)
    {
        return $model->getVideos();
    }

    public function getImages(Model $model)
    {
        return $model->getImages();
    }
}
