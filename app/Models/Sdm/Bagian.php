<?php

namespace App\Models\Sdm;

use Illuminate\Database\Eloquent\Model;

class Bagian extends Model
{
    protected $table = 'bagian';
    protected $guarded = [];

    function jabatans()
    {
        $this->hasMany(Jabatan::class);
    }
}
