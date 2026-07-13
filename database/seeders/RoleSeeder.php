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

<<<<<<< HEAD
        $permissions = [
            'view-dashboard',
            'view-dashboard-kamar',
        ];
=======
        // Define Roles
        $superAdmin = Role::firstOrCreate(['name' => 'Super-Admin']);
        $staffSdm = Role::firstOrCreate(['name' => 'Staff-SDM']);
        $bagianUmum = Role::firstOrCreate(['name' => 'Bagian-Umum']);
        $keuangan = Role::firstOrCreate(['name' => 'Keuangan']);
        $administrasi = Role::firstOrCreate(['name' => 'Administrasi']);
        $guest = Role::firstOrCreate(['name' => 'Guest']);
        // Role 'Koordinator' dihapus — koordinator kini merupakan tugas tambahan
        // yang di-assign via tabel sdm_ruangan_koordinator, bukan role Spatie

        // Fetch all permissions currently in database
        $allPermissions = Permission::all()->pluck('name')->toArray();
        $commonPermissions = ['view-dashboard', 'view-dashboard-kamar', 'view-profile-jadwal-tugas-saya'];
        
        // (Koordinator tidak lagi memerlukan permission khusus via Role)

>>>>>>> aad182c (feat: implement koordinator as supplementary assignment/task instead of role)

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        #assign permission to role
        $guestRole->givePermissionTo(['view-dashboard', 'view-dashboard-kamar']);
    }
}
