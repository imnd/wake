<?php

use App\Models\Memorial;
use App\Traits\MediaServiceTrait;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * @package Tests\Feature
 */
class MemorialsTest extends TestCase
{
    use MediaServiceTrait;

    protected string $prefix = 'memorials';
    private Memorial $memorial;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->memorial = Memorial::factory()->create($this->getMemorialData());

        $this->addMedia('image');
        $this->addMedia('video');
    }

    private function addMedia($type)
    {
        $method = 'get' . ucfirst($type);
        for ($i = 0; $i < $this->faker->numberBetween(2, 5); $i++) {
            $uploadPath = $this->putToStorage('memorial', $this->$method());
            $this->memorial
                ->addMedia($this->getStoragePath($uploadPath))
                ->toMediaCollection();
        }
    }

    /**
     * @test
     */
    public function can_see_memorial()
    {
        $this->getRequest(['memorial', $this->memorial->id]);
    }

    /**
     * @test
     */
    public function can_see_memorials()
    {
        $this->getRequest(['memorials', $this->user->id]);
    }

    /**
     * @test
     */
    public function can_create_memorial()
    {
        $this->postRequest(['create'], $this->getMemorialData());
    }

    /**
     * @test
     */
    public function can_update_memorial()
    {
        $this->putRequest(['update', $this->memorial->id], $this->getMemorialData(), Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function can_upload_media()
    {
        $this->postRequest(
            ['upload-media', $this->memorial->id],
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
        $this->getRequest(['get-media', $this->memorial->id]);
    }

    /**
     * @test
     */
    public function can_get_videos()
    {
        $this->getRequest(['get-videos', $this->memorial->id]);
    }

    /**
     * @test
     */
    public function can_get_images()
    {
        $this->getRequest(['get-images', $this->memorial->id]);
    }

    /**
     * @test
     */
    public function can_delete_medium()
    {
        $this->deleteRequest(['delete-medium', $this->memorial->media()->first()->id]);
    }

    /**
     * @test
     */
    public function can_delete_media()
    {
        $this->deleteRequest(['delete-media', $this->memorial->id]);
    }

    /**
     * @test
     */
    public function can_upload_avatar()
    {
        $this->postRequest(
            ['upload-avatar', $this->memorial->id],
            $this->getImageUploadParams(),
            Response::HTTP_NO_CONTENT
        );
    }
}
