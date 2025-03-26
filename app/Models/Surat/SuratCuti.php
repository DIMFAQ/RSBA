<?php

namespace App\Models\Surat;

use App\Enums\StatusCuti;
use App\Models\Sdm\Karyawan;
use Illuminate\Database\Eloquent\Model;

class SuratCuti extends Model
{
    protected $table = 'surat_cuti';
    protected $guarded = [];

    protected $casts = [
        'status' => StatusCuti::class
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }
}
