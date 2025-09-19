<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class GiveUsersRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'user']);

        User::where('id', '!=', 1) // exclude user with id 1
        ->get()
            ->each(function ($user) use ($role) {
                $user->assignRole($role);
            });
    }
}
