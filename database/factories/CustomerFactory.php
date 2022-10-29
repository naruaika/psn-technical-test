<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'uuid' => fake()->unique()->uuid(),
            'name' => fake()->unique()->name(),
            'title' => fake()->title(),
            'gender' => fake()->randomElement(['M', 'F']),
            'phone_number' => fake()->unique()->e164PhoneNumber(),
            'avatar' => '', //fake()->image('public/storage', 512, 512, null, false),
            'email' => fake()->unique()->email(),
        ];
    }
}
