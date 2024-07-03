<?php

namespace Tests\Traits;

use Illuminate\Http\Response;

trait MediaTestTrait
{
    protected function addMedia($type, $count = null): void
    {
        $method = 'get' . ucfirst($type);

        if (is_null($count)) {
            $count = $this->faker->numberBetween(2, 5);
        }
        for ($i = 0; $i < $count; $i++) {
            $uploadPath = $this->putToStorage('memorial', $this->$method());
            $this->model
                ->addMedia($this->getStoragePath($uploadPath))
                ->toMediaCollection();
        }
    }

    /**
     * @test
     */
    public function can_upload_medium()
    {
        $this->postRequest(
            ['upload-medium', $this->model->id],
            $this->getVideoUploadParams(),
            Response::HTTP_OK
        );
    }

    /**
     * @test
     */
    public function can_upload_media()
    {
        $this->postRequest(
            ['upload-media', $this->model->id],
            [
                'files' => [
                    $this->getImage(),
                    $this->getImage(),
                    $this->getImage(),
                ],
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @test
     */
    public function can_get_media()
    {
        $this->getRequest(['get-media', $this->model->id]);
    }

    /**
     * @test
     */
    public function can_get_videos()
    {
        $this->getRequest(['get-videos', $this->model->id]);
    }

    /**
     * @test
     */
    public function can_get_images()
    {
        $this->getRequest(['get-images', $this->model->id]);
    }
}
