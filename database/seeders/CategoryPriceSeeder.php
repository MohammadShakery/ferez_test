<?php

namespace Database\Seeders;

use App\Models\categoryPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        categoryPrice::query()->create([
            'name' => 'ارزها'
        ]);
        categoryPrice::query()->create([
            'name' => 'فلزات'
        ]);
    }
}
