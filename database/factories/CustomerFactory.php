<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //creating data fake of table customers
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'full_name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'type_identification' => random_int(1, 3),
            'identification' => $this->faker->phoneNumber()
        ];
    }
}
