<?php

namespace Database\Factories;

use App\Domain\MasterData\Enums\WarehouseType;
use App\Domain\MasterData\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Warehouse>
 */
class WarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->city().' '.$this->faker->randomElement(['Store', 'Warehouse']),
            'code' => strtoupper($this->faker->unique()->bothify('WH-###')),
            'type' => $this->faker->randomElement(WarehouseType::cases()),
            'city' => $this->faker->city(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'manager_name' => $this->faker->name(),
            'is_active' => true,
        ];
    }
}
