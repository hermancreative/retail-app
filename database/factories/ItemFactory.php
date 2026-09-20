<?php

namespace Database\Factories;

use App\Domain\MasterData\Models\Item;
use App\Domain\MasterData\Models\ItemCategory;
use App\Domain\MasterData\Models\UnitOfMeasure;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => strtoupper($this->faker->unique()->bothify('SKU-####')),
            'name' => ucfirst($this->faker->unique()->word().' '.$this->faker->word()),
            'item_category_id' => ItemCategory::factory(),
            'unit_of_measure_id' => UnitOfMeasure::factory(),
            'description' => $this->faker->optional()->sentence(),
            'cost_price' => $this->faker->randomFloat(2, 1, 500),
            'selling_price' => $this->faker->randomFloat(2, 1, 1000),
            'reorder_point' => $this->faker->numberBetween(0, 50),
            'is_active' => true,
        ];
    }
}
