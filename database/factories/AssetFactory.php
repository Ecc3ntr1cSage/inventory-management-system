<?php

namespace Database\Factories;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'model' => $this->faker->word,
            'registration_no' => $this->faker->unique()->regexify('[A-Z]{3}-[0-9]{3}'),
            'is_available' => $this->faker->boolean(80), // 80% chance of being true
        ];
    }
}
