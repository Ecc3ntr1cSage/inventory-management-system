<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Asset;
use App\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'asset_id' => $this->faker->optional()->randomElement(Asset::pluck('id')),
            'description' => $this->faker->sentence,
            'reason' => $this->faker->sentence,
            'position' => $this->faker->jobTitle,
            'department' => $this->faker->word,
            'location' => $this->faker->city,
            'application_date' => $this->faker->date(),
            'date_issued' => $this->faker->optional()->date(),
            'date_returned' => $this->faker->optional()->date(),
            'handler' => $this->faker->optional()->name,
            'receiver' => $this->faker->optional()->name,
            'status' => 0,
        ];
    }
}
