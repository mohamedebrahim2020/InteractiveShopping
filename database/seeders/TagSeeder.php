<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::insert([
            ['name' => 'bad packaging', 'category_tag_id' => '1'],
            ['name' => 'bad product', 'category_tag_id' => '1'],
            ['name' => 'bad transportation', 'category_tag_id' => '1'],
            ['name' => 'fair packaging', 'category_tag_id' => '2'],
            ['name' => 'fair product', 'category_tag_id' => '2'],
            ['name' => 'fair transportation', 'category_tag_id' => '2'],
            ['name' => 'excellent packaging', 'category_tag_id' => '3'],
            ['name' => 'excellent product', 'category_tag_id' => '3'],
            ['name' => 'excellent transportation', 'category_tag_id' => '3'],

        ]);
    }
}
