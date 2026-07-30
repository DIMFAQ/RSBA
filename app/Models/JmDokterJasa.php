<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JmDokterJasa extends Model
{
    protected $table = 'view_jm_dokter_jasa';
    public $timestamps = false;

    protected $fillable = [
        'jm_pasien_id',
        'dokter',
        'status_dokter',
        'status_label',
        'jumlah_visit',
        'jm_prosentase_id',
        'status_jasa',
        'jasa',
    ];
}
