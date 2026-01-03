<?php

namespace App\Models\Akreditasi;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AkreElement extends Model
{
    protected $table = 'akre_elements';
    protected $guarded = ['id'];

    protected $casts = [
        'methode' => 'array'
    ];


    public function bab(): BelongsTo
    {
        return $this->belongsTo(AkreBabElement::class, 'akre_bab_id', 'id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(AkreFiles::class, 'element_id', 'id');
    }

    public function validate_user()
    {
        return $this->belongsTo(User::class, 'validated_by', 'id');
    }

    public function getValidatorAttribute(): string
    {
        return $this->validate_user?->karyawan?->nama ?? 'n/a';
    }
}
