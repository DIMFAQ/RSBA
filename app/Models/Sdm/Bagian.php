<?php

namespace App\Models\Sdm;

use Illuminate\Database\Eloquent\Model;

class Bagian extends Model
{
    protected $table = 'bagian';
    protected $guarded = [];

    function jabatans()
    {
        return $this->hasMany(Jabatan::class, 'bagian_id', 'id');
    }
}
