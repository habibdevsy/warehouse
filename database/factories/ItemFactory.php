<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'commercial_name' => fake()->name(),
            'price' => fake()->numberBetween(100,5000),
            'quantity' => fake()->numberBetween(1,200),
            'category_id' => function () {
                if ($category = Category::inRandomOrder()->first()) {
                    return $category->id;
                }
                return CategoryTableSeeder::class;
            }
        ];
    }
}
