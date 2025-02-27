<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::all()->random();

        if ($product->id % 2 === 1) {
            $score = random_int(3, 5); // better score
        } else {
            $score = random_int(1, 3); // worse score
        }

        return [
            'product_id' => Product::all()->random()->id,
            'path' => fake()->url(),
        ];
    }
}
