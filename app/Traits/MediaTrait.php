<?php

namespace App\Traits;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait MediaTrait
{
    protected function putToStorage(string $path, UploadedFile $file): string
    {
        return $this->getDisk()->put($path, $file);
    }

    protected function getStoragePath(string $path = ''): string
    {
        return $this->getDisk()->path($path);
    }

    protected function getDisk(): Filesystem
    {
        return Storage::disk(config('media-library.disk_name'));
    }
}
