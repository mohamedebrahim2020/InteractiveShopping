<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderStatus::insert([
            ['status_name_en' => 'ordered','status_name_ar' => 'مطلوب'],
            ['status_name_en' => 'cancelled','status_name_ar' => 'ملغي'],
            ['status_name_en' => 'delivered','status_name_ar' => 'تم التسليم'],
        ]);
    }
}
