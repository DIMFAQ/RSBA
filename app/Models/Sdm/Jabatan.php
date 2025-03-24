<?php

namespace App\Models\Sdm;

use App\Models\Sdm\Bagian;
use App\Models\Sdm\KaryawanJabatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jabatan extends Model
{
    protected $table = 'sdm_jabatan';
    protected $guarded = [];

    function atasan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'parent_id');
    }

    function bawahan(): HasMany
    {
        return $this->hasMany(Jabatan::class, 'parent_id');
    }

    // to get history jabatan
    function jabatans()
    {
        return $this->hasMany(KaryawanJabatan::class);
    }

    function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }
}
