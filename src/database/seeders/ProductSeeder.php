<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Catalog\Product;
use Illuminate\Database\Seeder;

final class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds for products table.
     */
    public function run(): void
    {
        Product::factory()
            ->count(count: 10000)
            ->create();
    }
}
