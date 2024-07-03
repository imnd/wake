<?php

use App\Models\Memorial;
use App\Traits\MediaTrait;
use Tests\TestCase;
use Tests\Traits\MediaTestTrait;

/**
 * @package Tests\Feature
 */
class MemorialsMediaTest extends TestCase
{
    use MediaTrait;
    use MediaTestTrait;

    protected string $prefix = 'memorials';

    private Memorial $model;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = Memorial::factory()->create($this->getMemorialData());

        $this->addMedia('image');
        $this->addMedia('video');
    }

    /**
     * @test
     */
    public function can_delete_medium()
    {
        $this->deleteRequest(['media.delete-medium', $this->model->media()->first()->id]);
    }

    /**
     * @test
     */
    public function can_delete_media()
    {
        $this->deleteRequest(['media.delete-media', $this->model->id]);
    }
}
