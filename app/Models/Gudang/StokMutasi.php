<?php

namespace App\Models\Gudang;

use Illuminate\Database\Eloquent\Model;

class StokMutasi extends Model
{
    protected $table = "um_stok_mutasi";
    protected $guarded = ['id'];

    private const JENIS_MUTASI_NEGATIF = [
        'DISTRIBUSI' => true,
        'RETUR_BELI' => true,
        'RUSAK' => true,
        'HILANG' => true,
        'TRANSFER_KELUAR' => true,
        'ADJUSTMENT_MINUS' => true,
        'OPNAME_MISSING' => true,
        'OPNAME_WRITE_OFF' => true
    ];

    public function getJumlahBersihAttribute(): int
    {
        return isset(self::JENIS_MUTASI_NEGATIF[$this->jenis_mutasi])
            ? -abs($this->jumlah)
            : abs($this->jumlah);
    }
}
