<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //creating data fake of table products
        return [
            'name' => $this->faker->name(),
            'code_product' => $this->faker->uuid(),
            'value' => $this->faker->randomNumber(5),
            'url_image' => $this->faker->imageUrl(1280, 720),
        ];
    }
}
