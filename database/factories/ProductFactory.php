<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 1000), // السعر بين 10 و 1000
            'image' => 'https://picsum.photos/seed/' . fake()->word() . '/400/300', // صورة عشوائية
            'stock' => fake()->numberBetween(0, 100),
        ];
    }
}
