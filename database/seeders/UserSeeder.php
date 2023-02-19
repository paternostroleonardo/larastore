<?php

namespace Database\Seeders;

use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //default user admin seeder

        $user = User::where('email', 'admin@larastore.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'admin',
                'email' => 'admin@larastore.com',
                'email_verified_at' => now(),
                'password' => bcrypt(env('USER_PASSWORD')), // password
                //'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ]);
        }

        if (!$user->hasRole('root')) {
            app(PermissionRegistrar::class)->setPermissionsTeamId(-intval($user->id));
            $user->assignRole('root');
        }
    }
}
