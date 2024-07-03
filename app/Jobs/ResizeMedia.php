<?php

namespace App\Jobs;

use App\Models\Bouquet;
use App\Models\Memorial;
use App\Traits\MediaTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ResizeMedia implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use MediaTrait;

    private Bouquet | Memorial $model;
    private string $uploadPath;
    private string $mimeType;

    public function __construct(
        Bouquet | Memorial $model,
        string $uploadPath,
        string $mimeType,
    ) {
        $this->model = $model;
        $this->uploadPath = $uploadPath;
        $this->mimeType = $mimeType;
    }

    public function handle(): void
    {
        $mimeType = substr($this->mimeType, 0, strpos($this->mimeType, '/'));
        $path = $this->getStoragePath($this->uploadPath);

        $previewMedia = $this->model
            ->addMedia($path)
            ->preservingOriginal()
            ->toMediaCollection("$mimeType-preview");

        if ($mimeType === 'image') {
            list($width, $height) = getimagesize($path);
            $previewMedia->update([
                'original_width' => $width,
                'original_height' => $height
            ]);
        }

        $this->model
            ->addMedia($path)
            ->preservingOriginal()
            ->toMediaCollection("$mimeType-thumb");
    }
}
