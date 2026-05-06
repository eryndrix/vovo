<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Catalog\Category;
use Illuminate\Database\Seeder;

final class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds for categories table.
     */
    public function run(): void
    {
        Category::factory()
            ->count(count: 10)
            ->create();
    }
}
