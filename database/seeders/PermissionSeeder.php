<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'add-menu',
            'view-dokter',
            'export-karyawan',
            'create-cuti-other-karyawan',
            'tanda-tangan-digital',
            'terima-pembelian',

            // Jasmed
            'jasmed-bpjs',
            'jasmed-tunai',
            'jasmed-jkmd',

            // umum
            'approval-maintenance',
            'finish-opname-gudang',
            'assesor-akreditasi',
            'sekretariat-akreditasi'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
