<?php

namespace Database\Factories;

use App\Models\Bouquet;
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
            'status' => Bouquet::STATUS_PAID,
        ];
    }
}
