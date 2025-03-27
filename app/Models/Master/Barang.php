<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{

    protected $table = 'um_barang';
    protected $guarded = [];

    public function satuan()
    {
        return $this->belongsTo(BarangSatuan::class, 'satuan_id', 'id');
    }

    public function kategori()
    {
        return $this->belongsTo(BarangKategori::class, 'kategori_id', 'id');
    }
}
