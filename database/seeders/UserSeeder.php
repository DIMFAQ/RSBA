<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sdm\Karyawan;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawan = Karyawan::firstOrCreate([
            'nip' => '0000000000',
            'nik' => '0000000000000000',
            'nama' => 'Super Admin',
            'tgl_lahir' => now(),
            'hp' => '-',
            'prov' => '-',
            'kab' => '-',
            'kec' => '-',
            'desa' => '-',
            'alamat' => '-',
            'agama' => 'islam',
            'status' => 'tetap',
            'tgl_masuk' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create User
        $userAdmin = User::firstOrCreate([
            'email' => 'admin@admin.com',
            'password' => Hash::make('secret'),
            'karyawan_id' => $karyawan->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign Role
        $adminRole = Role::firstOrCreate(['name' => 'Super-Admin']);
        if ($adminRole) {
            $userAdmin->assignRole('Super-Admin');
        }
    }
}
