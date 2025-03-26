<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JmJasa extends Model
{
    protected $guarded = [];

    protected $table = 'jm_jasa';

    function prosentase(): BelongsTo
    {
        return $this->belongsTo(JmProsentase::class, 'jm_prosentase_id');
    }
}
