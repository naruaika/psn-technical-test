<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $customer = CustomerFactory::new()->create();

        return [
            'uuid' => fake()->unique()->uuid(),
            'customer_id' => $customer->id,
            'address' => fake()->address(),
            'district' => fake()->state(),
            'city' => fake()->city(),
            'province' => fake()->country(),
            'postal_code' => fake()->postcode(),
        ];
    }
}
