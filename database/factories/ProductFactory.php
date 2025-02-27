<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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

        $user = User::all()->random();

        if ($user->id % 2 === 1) {
            $score = random_int(3, 5); // better score
        } else {
            $score = random_int(1, 3); // worse score
        }

        return [
            'user_id' => User::all()->random()->id,
            'name' => fake()->sentence,
            'description' => fake()->text,
            'count' => fake()->randomNumber(),
            'price' => fake()->randomNumber(),
            'status' => fake()->randomElement([ProductStatus::Draft, ProductStatus::Published]),
        ];
    }
}
