<?php

use App\Models\Bouquet;
use App\Models\Memorial;
use App\Traits\MediaTrait;
use Tests\TestCase;
use Tests\Traits\MediaTestTrait;

/**
 * @package Tests\Feature
 */
class BouquetsMediaTest extends TestCase
{
    use MediaTrait;
    use MediaTestTrait;

    protected string $prefix = 'bouquets';

    private Bouquet $model;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $memorial = Memorial::factory()->create($this->getMemorialData());

        $this->model = Bouquet::factory()->create($this->getBouquetData($memorial));

        $this->addMedia('image', 1);
        $this->addMedia('video', 2);
    }

    /**
     * @test
     */
    public function can_delete_medium()
    {
        $this->prefix = 'media';
        $this->deleteRequest(['delete-medium', $this->model->media()->first()->id]);
    }

    /**
     * @test
     */
    public function can_delete_media()
    {
        $this->prefix = 'media';
        $this->deleteRequest(['delete-media', $this->model->id]);
    }
}
