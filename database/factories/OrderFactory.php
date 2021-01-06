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
        return [
            'total_price' => $this->faker->randomDigit,
            'payment_id' => 1,
            'address_id' => Address::factory(),
            'delivery_at' => "21-01-07 08:00:01",
            //Carbon::createFromFormat('Y-m-d H:i:s', "21-01-07 08:00:01"),
            'order_status_id' => 1,
        ];
    }
}
