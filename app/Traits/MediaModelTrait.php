<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Spatie\Image\Enums\Fit;

trait MediaModelTrait
{
    protected function addMediaConversions(string $disk): void
    {
        $this
            ->addMediaConversion('image-thumb')
            ->quality(config('media-library.conversions.image_quality'))
            ->fit(
                Fit::Crop,
                config("media-library.conversions.$disk.image.thumb.width"),
                config("media-library.conversions.$disk.image.thumb.height")
            )
            ->nonQueued();

        $this
            ->addMediaConversion('image-preview')
            ->quality(config('media-library.conversions.image_quality'))
            ->fit(
                Fit::Crop,
                config("media-library.conversions.$disk.image.preview.width"),
                config("media-library.conversions.$disk.image.preview.height")
            )
            ->nonQueued();

        $this->addMediaConversion('video-preview')
             ->width(config("media-library.conversions.$disk.video.preview.width"))
             ->height(config("media-library.conversions.$disk.video.preview.height"))
             ->extractVideoFrameAtSecond(config("media-library.conversions.video_screenshot_position"))
             ->performOnCollections('videos');

        $this->addMediaConversion('video-thumb')
             ->width(config("media-library.conversions.$disk.video.thumb.width"))
             ->height(config("media-library.conversions.$disk.video.thumb.height"))
             ->extractVideoFrameAtSecond(config("media-library.conversions.video_screenshot_position"))
             ->performOnCollections('videos');
    }

    public function getAllMedia(): Collection
    {
        return $this
            ->getVideos()
            ->merge($this->getImages());
    }

    public function getVideos(): Collection
    {
        return $this
            ->getMedia('video-preview')
            ->merge($this->getMedia('video-thumb'));
    }

    public function getImages(): Collection
    {
        return $this
            ->getImagePreviews()
            ->merge($this->getMedia('image-thumb'))
        ;
    }

    public function getImagePreviews(): Collection
    {
        return $this
            ->getMedia('image-preview')
        ;
    }
}
