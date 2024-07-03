<?php

namespace App\Jobs;

use App\Traits\MediaTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use ReflectionClass;

class ResizeAvatar implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use MediaTrait;

    private Model $model;
    private string $fileName;
    private string $filePath;

    public function __construct(
        Model $model,
        string $fileName,
        string $filePath,
    ) {
        $this->model = $model;
        $this->fileName = $fileName;
        $this->filePath = $filePath;
    }

    public function handle(): void
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($this->filePath);

        $reflect = new ReflectionClass($this->model);
        $entityName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $reflect->getShortName()));

        $configPath = "media-library.conversions.$entityName.avatar";
        // Image resize
        $image->cover(
            config("$configPath.width"),
            config("$configPath.height")
        );

        // Save the file
        $image->save($this->getStoragePath($avatar = "avatars/{$this->fileName}"));

        // Update the model
        $this->model->update(compact('avatar'));
    }
}
