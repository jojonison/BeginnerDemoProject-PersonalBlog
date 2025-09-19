<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        $createPermission = Permission::create(['name' => 'create articles']);
        $editPermission = Permission::create(['name' => 'edit articles']);
        $viewPermission = Permission::create(['name' => 'view articles']);

        $adminRole->givePermissionTo($createPermission, $editPermission, $viewPermission);
        $userRole->givePermissionTo($viewPermission);

        $user = User::find(1); // Example user with ID 1
        $user->assignRole('admin');
    }
}
