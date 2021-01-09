<?php

namespace Database\Seeders;

use App\Models\TagCategory;
use Illuminate\Database\Seeder;

class TagCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TagCategory::insert([
            ['uses' => 'for rate with one star'],
            ['uses' => 'for rate with 2 or 3 star'],
            ['uses' => 'for rate with 4 or 5 star'],
        ]);
    }
}
