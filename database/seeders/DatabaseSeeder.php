<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //execute class users seeders and fakes product
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UserSeeder::class);
        Customer::factory(20)->create();
        Product::factory(27)->create();
        User::factory(10)->create();
        Order::factory(13)->create();
    }
}
