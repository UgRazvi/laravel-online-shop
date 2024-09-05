<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // // {{-- Method : X-01 --}}
            // 'name' => $this->faker->name,
            // 'slug' => $this->faker->slug,
            // 'status' => $this->faker->boolean,

            // {{-- Method : X-02 --}}
            'name' => fake()->name(),
            'status' => rand(0,1),
            'slug' => fake()->name(),
        ];
    }
}
