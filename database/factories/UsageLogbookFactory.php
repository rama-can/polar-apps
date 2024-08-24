<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UsageLogbook>
 */
class UsageLogbookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = [1, 2, 3];
        return [
            'product_id' => $this->faker->randomElement($product),
            'name' => $this->faker->name(),
            'date' => $this->faker->dateTimeBetween('2024-08-01', '2024-08-30')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['MAHASISWA', 'PLP', 'DOSEN', 'PENELITI', 'LAINNYA']),
            'total_duration' => $this->faker->time('H:i'),
            'temperature' => $this->faker->randomFloat(2, 36, 37),
            'rh' => $this->faker->randomFloat(2, 36, 37),
            'note' => $this->faker->text(),
        ];
    }
}
