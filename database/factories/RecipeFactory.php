<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->words(3, true),
            'image' => fake()->imageUrl(),
            'description' => fake()->sentence(),
            'servings' => fake()->numberBetween(1, 4) . '人分',
            'tips' => fake()->sentence(),
            'status' => 1,
            'approval_at' => now(),
            'rejection_reason' => null,
        ];
    }
}
