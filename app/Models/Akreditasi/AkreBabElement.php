<?php

namespace App\Models\Akreditasi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AkreBabElement extends Model
{
    protected $table = 'akre_bab_elements';
    protected $guarded = ['id'];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(AkreChapter::class, 'chapter_id', 'id');
    }

    public function elements(): HasMany
    {
        return $this->hasMany(AkreElement::class, 'akre_bab_id', 'id');
    }
}
