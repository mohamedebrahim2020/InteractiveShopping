<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = (Carbon::now()->addDays(2)->isFriday()) ? Carbon::now()->addDays(3) : Carbon::now()->addDays(2);
        return [
            'total_price' => $this->faker->randomDigit,
            'payment_id' => 1,
            'address_id' => Address::factory(),
            'delivery_at' => $date,
            'order_status_id' => 1,
        ];
    }
}
