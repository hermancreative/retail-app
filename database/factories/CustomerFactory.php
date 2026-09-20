<?php

namespace Database\Factories;

use App\Domain\MasterData\Enums\CustomerType;
use App\Domain\MasterData\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type' => $this->faker->randomElement(CustomerType::cases()),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'credit_limit' => 0,
            'credit_terms_days' => 0,
            'is_active' => true,
        ];
    }
}
