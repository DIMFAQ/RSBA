<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('perusahaan')->insert(
            [
                'nama' => 'RS Bintang Amin',
                'alamat' => 'Jl. Prmamuka No. 27, Kemiling, Bandar Lampung',
                'telp' => '0721-123456',
                'email' => 'sdm@rspba.co.id',
                'singkatan' => 'RSBA',
                'hastags' => 'We Care, We Cure',
                'website' => 'http://rspba.co.id',
                'logo' => null,
            ]
        );
    }
}
