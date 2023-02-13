<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

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
        $users = User::all();

        foreach ($users as $key => $user) {
            if (!$user->hasRole('root')) {
                app(PermissionRegistrar::class)->setPermissionsTeamId(-intval($user->id));
                $user->assignRole('seller');
            }
        }

        $sellers = User::role('seller')->get();
        $customers = Customer::all();
        $products = Product::all();

        return [
            'product_id' => $products->random(),
            'customer_id' => $customers->random(),
            'seller_id' => $sellers->random(),
            'code_order' => $this->faker->uuid(),
            'status' => random_int(1, 3)
        ];
    }
}
