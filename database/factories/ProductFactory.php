<?php

namespace Database\Factories;

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
        return [
            'unique_key' => 'PRD' . fake()->unique()->numberBetween(1000, 9999),
            'product_title' => fake()->sentence(3),
            'product_description' => fake()->paragraph(),
            'style' => 'STYLE' . fake()->numberBetween(100, 999),
            'sanmar_mainframe_color' => fake()->colorName(),
            'size' => fake()->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL']),
            'color_name' => fake()->colorName(),
            'piece_price' => fake()->randomFloat(2, 10, 100),
        ];
    }
}
