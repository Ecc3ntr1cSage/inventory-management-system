<?php

namespace Database\Factories;

use App\Models\Stock;
use App\Models\Index;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Index>
 */
class IndexFactory extends Factory
{
    protected $model = Index::class;
    public function definition()
    {
        $stock = Stock::factory()->create();
        $inQuantity = $this->faker->boolean(70) ? $this->faker->numberBetween(1, 100) : null;
        $outQuantity = $inQuantity === null ? $this->faker->numberBetween(1, 100) : null;

        return [
            'stock_id' => $stock->id,
            'date' => $this->faker->date(),
            'reference_no' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{5}'),
            'in_quantity' => $inQuantity,
            'out_quantity' => $outQuantity,
            'balance' => $stock->balance + ($inQuantity ?? 0) - ($outQuantity ?? 0),
            'name' => User::factory()->create()->name,
        ];
    }
}
