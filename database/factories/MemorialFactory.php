<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Memorial>
 */
class MemorialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'default' => fake()->boolean(),
        ];
    }
}
