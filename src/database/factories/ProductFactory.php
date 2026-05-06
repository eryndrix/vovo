<?php

namespace Database\Factories;

use App\Models\Catalog\Category;
use App\Models\Catalog\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
final class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid7()->toString(),
            'name' => $this->faker->unique()->sentence(nbWords: 3),
            'price' => $this->faker->numberBetween(int1: 1000, int2: 1000000),
            'rating' => $this->faker->randomFloat(nbMaxDecimals: 1, min: 0, max: 5),
            'in_stock' => $this->faker->boolean(chanceOfGettingTrue: 85),
            'category_id' => Category::query()->inRandomOrder()->value(column: 'id'),
        ];
    }
}