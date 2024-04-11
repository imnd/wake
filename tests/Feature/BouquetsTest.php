<?php

use App\Models\Bouquet;
use App\Models\BouquetType;
use App\Models\Memorial;
use App\Traits\MediaServiceTrait;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * @package Tests\Feature
 */
class BouquetsTest extends TestCase
{
    use MediaServiceTrait;

    private Memorial $memorial;
    private Bouquet $bouquet;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->memorial = Memorial::factory()->create($this->getMemorialData());

        $this->bouquet = Bouquet::factory()->create($this->getBouquetData($this->memorial));

        $uploadPath = $this->putToStorage('bouquet', $this->getVideo());
        $this->bouquet
            ->addMedia($this->getStoragePath($uploadPath))
            ->toMediaCollection();

        Bouquet::factory()
            ->count(4)
            ->sequence(
                ['status' => 'paid'],
                ['status' => 'paid'],
                ['status' => 'unpaid'],
                ['status' => 'unpaid'],
            )
            ->create([
                'memorial_id' => $this->memorial->id,
                'user_id' => $this->user->id,
            ]);
    }

    /**
     * @test
     */
    public function can_get_bouquets()
    {
        $this->prefix = 'memorials';
        $result = $this->getRequest(['bouquets', $this->memorial->id]);
        $this->assertArrayHasKey('condolences', $result[0]);
        $this->assertArrayHasKey('from', $result[0]);
        $this->assertArrayNotHasKey('error', $result);
    }

    /**
     * @test
     */
    public function can_create_bouquet()
    {
        $this->prefix = 'memorials';
        $this->postRequest(['create-bouquet', $this->memorial->id], [
            'condolences' => $this->faker->text(128),
            'from' => $this->faker->firstName . ' ' . fake()->lastName,
            'type_id' => BouquetType::inRandomOrder()->first()->id,
        ]);
    }

    /**
     * @test
     */
    public function can_update_bouquet()
    {
        $this->prefix = 'bouquets';
        $this->putRequest(['update', $this->bouquet->id], [
            'condolences' => $this->faker->text(128),
            'from' => $this->faker->firstName . ' ' . fake()->lastName,
            'status' => $this->faker->randomElement([Bouquet::STATUS_PAID, Bouquet::STATUS_UNPAID]),
        ], Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function can_upload_medium()
    {
        $this->prefix = 'bouquets';
        $this->postRequest(
            ['upload-medium', $this->bouquet->id],
            $this->getVideoUploadParams(),
            Response::HTTP_OK
        );
    }

    /**
     * @test
     */
    public function can_delete_medium()
    {
        $this->prefix = 'media';
        $this->deleteRequest(['delete', $this->bouquet->media()->first()->id]);
    }
}
