<?php

namespace App\Models\Sdm;

use App\Enums\TingkatPendidikan;
use Illuminate\Database\Eloquent\Model;

class KaryawanPendidikan extends Model
{
    protected $table = 'sdm_kary_pendidikan';
    protected $guarded = [];

    protected $casts = [
        'tingkat' => TingkatPendidikan::class
    ];
}
