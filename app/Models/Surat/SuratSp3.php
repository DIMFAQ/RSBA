<?php

namespace App\Models\Surat;

use App\Models\Surat\SuratSp3Detail;
use Illuminate\Database\Eloquent\Model;

class SuratSp3 extends Model
{
    protected $table = 'surat_sp3';
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(SuratSp3Detail::class, 'sp3_id', 'id');
    }

    function getMethodBayarAttribute()
    {
        $mapping = [
            'tunai' => 'Tunai',
            'trf'   => 'Transfer',
            'giro'  => 'Giro',
        ];

        return $mapping[$this->attributes['bayar']] ?? $this->attributes['bayar'];
    }
}
