<?php

namespace App\Models\Akreditasi;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AkreChapter extends Model
{
    protected $table = "akre_chapter";
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'pic_id', 'id');
    }

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(AkreKegiatan::class, 'kegiatan_id', 'id');
    }

    public function babs(): HasMany
    {
        return $this->hasMany(AkreBabElement::class, 'chapter_id', 'id');
    }
}
