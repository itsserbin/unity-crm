<?php

namespace Database\Factories\Catalog;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Catalog\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->numberBetween(1000, 10000),
            'sku' => fake()->unique()->text(10),
            'price' => fake()->randomFloat(2, 10, 100),
            'discount_price' => fake()->randomFloat(2, 5, 50),
            'title' => fake()->text(50),
            'availability' => fake()->numberBetween(0, 1),
        ];
    }
}
