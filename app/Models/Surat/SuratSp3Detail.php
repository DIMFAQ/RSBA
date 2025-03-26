<?php

namespace App\Models\Surat;

use App\Models\Surat\SuratSp3;
use Illuminate\Database\Eloquent\Model;

class SuratSp3Detail extends Model
{
    protected $table = 'surat_sp3_details';
    protected $guarded = [];


    public function sp3()
    {
        return $this->belongsTo(SuratSp3::class, 'sp3_id', 'id');
    }
}
