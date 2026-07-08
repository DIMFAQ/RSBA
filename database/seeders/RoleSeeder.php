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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define Roles
        $superAdmin = Role::firstOrCreate(['name' => 'Super-Admin']);
        $staffSdm = Role::firstOrCreate(['name' => 'Staff-SDM']);
        $bagianUmum = Role::firstOrCreate(['name' => 'Bagian-Umum']);
        $keuangan = Role::firstOrCreate(['name' => 'Keuangan']);
        $administrasi = Role::firstOrCreate(['name' => 'Administrasi']);
        $guest = Role::firstOrCreate(['name' => 'Guest']);

        // Fetch all permissions currently in database
        $allPermissions = Permission::all()->pluck('name')->toArray();

        // 1. SDM permissions
        $sdmKeywords = ['kepegawaian', 'karyawan', 'dokter', 'cuti', 'sp3', 'jasmed', 'akreditasi', 'verifikasi', 'tanda-tangan-digital', 'export-karyawan', 'bagian', 'jabatan', 'ruangan', 'spesialis', 'surat', 'gaji', 'view-master'];
        $sdmPermissions = array_filter($allPermissions, function ($permission) use ($sdmKeywords) {
            foreach ($sdmKeywords as $keyword) {
                if (str_contains(strtolower($permission), strtolower($keyword))) {
                    return true;
                }
            }
            return false;
        });
        $sdmPermissions[] = 'view-dashboard';
        $sdmPermissions[] = 'view-dashboard-kamar';
        $staffSdm->syncPermissions(array_unique($sdmPermissions));

        // 2. Umum permissions
        $umumKeywords = ['umum', 'supplier', 'kategori', 'satuan', 'penyimpanan', 'barang', 'pembelian', 'distribusi', 'gudang', 'asset', 'opname', 'maintenance', 'pengajuan', 'laporang'];
        $umumPermissions = array_filter($allPermissions, function ($permission) use ($umumKeywords) {
            foreach ($umumKeywords as $keyword) {
                if (str_contains(strtolower($permission), strtolower($keyword))) {
                    return true;
                }
            }
            return false;
        });
        $umumPermissions[] = 'view-dashboard';
        $umumPermissions[] = 'view-dashboard-kamar';
        $bagianUmum->syncPermissions(array_unique($umumPermissions));

        // 3. Keuangan permissions
        $keuanganKeywords = ['keuangan', 'hutang', 'piutang', 'rekanan', 'coa', 'jurnal', 'akuntansi'];
        $keuanganPermissions = array_filter($allPermissions, function ($permission) use ($keuanganKeywords) {
            foreach ($keuanganKeywords as $keyword) {
                if (str_contains(strtolower($permission), strtolower($keyword))) {
                    return true;
                }
            }
            return false;
        });
        $keuanganPermissions[] = 'view-dashboard';
        $keuanganPermissions[] = 'view-dashboard-kamar';
        $keuangan->syncPermissions(array_unique($keuanganPermissions));

        // 4. Administrasi permissions
        $admKeywords = ['administrasi', 'pasien', 'registrasi'];
        $admPermissions = array_filter($allPermissions, function ($permission) use ($admKeywords) {
            foreach ($admKeywords as $keyword) {
                if (str_contains(strtolower($permission), strtolower($keyword))) {
                    return true;
                }
            }
            return false;
        });
        $admPermissions[] = 'view-dashboard';
        $admPermissions[] = 'view-dashboard-kamar';
        $administrasi->syncPermissions(array_unique($admPermissions));

        // 5. Guest permissions
        $guestPermissions = ['view-dashboard', 'view-dashboard-kamar'];
        $guest->syncPermissions($guestPermissions);
    }
}
