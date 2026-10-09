<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User; // Impor User

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shelter>
 */
class ShelterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Kandang ' . $this->faker->unique()->word(),
            'description' => $this->faker->sentence(),
            'capacity' => $this->faker->numberBetween(20, 100),

            // Asumsikan user_id 1 adalah admin/user pertama Anda.
            'user_id' => User::first()->id ?? User::factory(),
        ];
    }
}
