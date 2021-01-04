<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Order;
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
            'delivery_at' => $this->faker->date ,
            'order_status_id' => 1,
        ];
    }
}
