<?php

use App\Helpers\Statuses;
use App\Models\Bouquet;
use App\Models\BouquetType;
use App\Models\Memorial;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * @package Tests\Feature
 */
class BouquetsTest extends TestCase
{
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
            'status' => $this->faker->randomElement([Statuses::STATUS_PAID, Statuses::STATUS_UNPAID]),
        ], Response::HTTP_OK);
    }
}
