<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::findOrCreate('root');
        Role::findOrCreate('seller');
        Role::findOrCreate('inspector');

        $roles = Role::all();

        foreach ($roles as $role) {
            $role->update(['guard_name' => 'api']);
        }
    }
}
