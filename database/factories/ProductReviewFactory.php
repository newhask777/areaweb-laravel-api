<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Productreview>
 */
class ProductReviewFactory extends Factory
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

        $product = Product::all()->random();

        if ($product->id % 2 === 1) {
            $score = random_int(3, 5); // better score
        } else {
            $score = random_int(1, 3); // worse score
        }

        return [
            'user_id' => User::all()->random()->id,
            'product_id' => Product::all()->random()->id,
            'text' => fake()->text,
            'rating' => fake()->randomNumber(),

        ];
    }
}
