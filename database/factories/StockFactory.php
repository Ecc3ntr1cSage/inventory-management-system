<?php

namespace Database\Factories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    protected $model = Stock::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'group' => $this->faker->optional()->word(),
            'location' => $this->faker->optional()->city(),
            'balance' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
