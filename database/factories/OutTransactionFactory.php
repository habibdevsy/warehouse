<?php

namespace Database\Factories;

use App\Models\OutTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OutTransaction>
 */
class OutTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => fake()->numberBetween(1000,5000),
            'quantity' => fake()->numberBetween(1,200),
            'item_id' => function () {
                if ($item = OutTransaction::inRandomOrder()->first()) {
                    return $item->id;
                }
                return ItemTableSeeder::class;
            }
        ];
    }
}
