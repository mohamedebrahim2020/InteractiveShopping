<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title() ,
            'address_address' => $this->faker->address ,
            'address_type' => "Home",
            'address_latitude' => $this->faker->latitude ,
            'address_longitude' => $this->faker->longitude ,
            'address_description' => $this->faker->text(20) ,
            'icon' => $this->faker->imageUrl,
        ];
    }
}
