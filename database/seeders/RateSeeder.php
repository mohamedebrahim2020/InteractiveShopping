<?php

namespace Database\Seeders;

use App\Models\Rate;
use Illuminate\Database\Seeder;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rate::insert([
            ['rank' => 1, 'category_id' => '1'],
            ['rank' => 2, 'category_id' => '2'],
            ['rank' => 3, 'category_id' => '2'],
            ['rank' => 4, 'category_id' => '3'],
            ['rank' => 5, 'category_id' => '3'],
        ]);
    }
}
