<?php

namespace App\Services;

use App\Models\Gudang\Stok;
use App\Models\Gudang\StokMutasi;
use Illuminate\Container\Attributes\DB;

class StokMutasiService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function tambahStok(int $barangId, int $stokId, int $jumlah, string $jenisMutasi, object $referensi, ?string $keterangan = null): StokMutasi
    {
        return DB::transaction(function () use ($barangId, $stokId, $jumlah, $jenisMutasi, $referensi, $keterangan) {

            $stokSebelum = 0;
            $stokSesudah = $stokSebelum + $jumlah;

            return StokMutasi::create([
                'stok_id' => $stokId,
                'barang_id' => $barangId,
                'jenis_mutasi' => $jenisMutasi,
                'jumlah' => $jumlah,
                'multiplier' => 1,
                'jumlah_bersih' => $jumlah,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $keterangan,
                'referensi_type' => $referensi,
                'referensi_id' => $stokId,
                'created_by' => auth()->user()->id,
                'is_posted' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    public function kurangiStok(int $stokId, int $barangId, int $jumlah, string $jenisMutasi, object $referensi, ?string $keterangan = null): StokMutasi
    {
        return DB::transaction(function () use ($stokId, $barangId, $jumlah, $jenisMutasi, $referensi, $keterangan) {

            $stoks = Stok::lockForUpdate()->findOrFail($stokId);

            if ($stoks->stok < $jumlah) {
                throw new \Exception("Stok tidak mencukupi. Stok tersedia: {$stoks->barang->nama}");
            }

            $stokSebelum = $stoks->stok;
            $stokSesudah = $stoks->stok - $jumlah;

            return StokMutasi::create([
                'stok_id' => $stokId,
                'barang_id' => $barangId,
                'jenis_mutasi' => $jenisMutasi,
                'jumlah' => $jumlah,
                'multiplier' => -1,
                'jumlah_bersih' => $jumlah,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $keterangan,
                'referensi_type' => $referensi,
                'referensi_id' => $stokId,
                'created_by' => auth()->user()->id,
                'is_posted' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    public function historyStok(): void {}


    private function getJenisMutasi() {}
}
