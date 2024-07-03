<?php

namespace App\Services;

use App\Jobs\ResizeAvatar;
use App\Jobs\ResizeMedia;
use App\Models\Bouquet;
use App\Models\Memorial;
use App\Traits\MediaTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class MediaService
{
    use MediaTrait;

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

    public function uploadMedia(Bouquet | Memorial $model, array $files): void
    {
        foreach ($files as $file) {
            $this->uploadMedium($model, $file);
        }
    }

    public function uploadMedium(Bouquet | Memorial $model, UploadedFile $file): void
    {
        ResizeMedia::dispatch(
            model: $model,
            uploadPath: $this->putToStorage('bouquet', $file),
            mimeType: $file->getMimeType(),
        );
    }
}
