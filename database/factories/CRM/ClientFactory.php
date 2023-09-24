<?php

namespace Database\Factories\CRM;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    final public function definition(): array
    {
        return [
            'id' => fake()->unique()->numberBetween(100, 999999),
            'full_name' => fake()->name,
            'phones' => [fake()->phoneNumber],
            'emails' => [fake()->email],
        ];
    }
}
