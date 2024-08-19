<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CalibrationLogbook>
 */
class CalibrationLogbookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = ['1', '2', '3'];
        return [
            'product_id' => $this->faker->randomElement($product),
            'date' => $this->faker->dateTimeBetween('2024-06-17', '2024-08-17')->format('Y-m-d'),
            'technician' => $this->faker->name(),
            'institution' => $this->faker->company(),
            'document' => $this->faker->url(),
        ];
    }
}
