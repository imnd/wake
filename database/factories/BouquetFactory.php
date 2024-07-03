<?php

namespace Database\Factories;

use App\Helpers\Statuses;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bouquet>
 */
class BouquetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'condolences' => fake()->text(128),
            'from' => fake()->firstName . ' ' . fake()->lastName,
            'status' => Statuses::STATUS_PAID,
        ];
    }
}
