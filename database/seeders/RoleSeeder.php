<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Super-Admin']);
        $guestRole  = Role::firstOrCreate(['name' => 'Guest']);

        $permissions = [
            'view-dashboard',
            'view-dashboard-kamar',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        #assign permission to role
        $guestRole->givePermissionTo(['view-dashboard', 'view-dashboard-kamar']);
    }
}
