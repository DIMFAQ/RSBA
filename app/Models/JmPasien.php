<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JmPasien extends Model
{
    protected $guarded = [];
    protected $table = 'jm_pasien';

    function dokter(): HasMany
    {
        return $this->hasMany(JmDokter::class, 'jm_pasien_id', 'id');
    }

    function prosentase(): HasOne
    {
        return $this->hasOne(JmProsentase::class);
    }
}
