<?php

namespace Database\Seeders;

use App\Models\OrderCancellationReason;
use Illuminate\Database\Seeder;

class OrderCancellationReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderCancellationReason::insert([
            ['reason_desc_en' => 'i changed my mind','reason_desc_ar' => 'غيرت رأيي'],
            ['reason_desc_en' => 'i ordered the wrong items','reason_desc_ar' => 'طلبت المنتجات الخطأ'],
            ['reason_desc_en' => 'i wont be here','reason_desc_ar' => 'لن اكون هنا'],
            ['reason_desc_en' => 'other reason','reason_desc_ar' => 'سبب اخر'],
        ]); 
    }
}
