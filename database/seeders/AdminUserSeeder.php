<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'مدير النظام ',
            'email' => 'admin@hr.com',
            'role_id' => 1,
            'image' => 'user.png',
            'password' => bcrypt('11111111'),
        ]);
        $role = Role::create(['name' => 'Admin']);
        // Fetch all permissions as an array
        $permissions = Permission::all();
        // Assign all permissions to the role
        $role->syncPermissions($permissions);
        // Assign the role to the user
        $user->assignRole($role->name);
    }
}
