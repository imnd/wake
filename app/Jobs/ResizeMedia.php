<?php

namespace App\Jobs;

use App\Traits\MediaServiceTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResizeMedia implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use MediaServiceTrait;

    private Model $model;
    private string $uploadPath;
    private string $mimeType;

    public function __construct(
        Model $model,
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

        $this->model
            ->addMedia($this->getStoragePath($this->uploadPath))
            ->preservingOriginal()
            ->toMediaCollection("$mimeType-preview");
        $this->model
            ->addMedia($this->getStoragePath($this->uploadPath))
            ->preservingOriginal()
            ->toMediaCollection("$mimeType-thumb");
    }
}
