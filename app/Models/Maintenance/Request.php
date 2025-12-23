<?php

namespace App\Models\Maintenance;

use App\Models\Assets\AssetBarang;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Request extends Model
{
    protected $table = 'asset_maintc_requests';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'lampiran' => 'array',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(AssetBarang::class, 'asset_id', 'id');
    }

    public function getUserRequestAttribute(): ?string
    {
        if ($this->relationLoaded('user_req') && $this->user_req?->relationLoaded('karyawan')) {
            return $this->user_req?->karyawan?->nama ?? '-';
        }

        return optional(optional($this->user_req)?->karyawan)?->nama;
    }

    public function getUserVerifyAttribute(): ?string
    {

        if ($this->relationLoaded('user_verif') && $this->user_verif?->relationLoaded('karyawan')) {
            return $this->user_verif?->karyawan?->nama ?? '-';
        }

        return optional(optional($this->user_verif)?->karyawan)?->nama;
    }

    public function user_req()
    {
        return $this->belongsTo(User::class, 'user_req_id', 'id');
    }

    public function user_verif(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_verify_id', 'id');
    }

    // public function getUserRequestAttribute(): ?string
    // {
    //     return $this->user_req->karyawan->nama;
    // }

    // public function getUserVerifyAttribute(): ?string
    // {
    //     return $this->user_verif->karyawan->nama ?? null;
    // }

    public function jadwal(): HasOne
    {
        return $this->hasOne(Jadwal::class, 'maintc_request_id', 'id');
    }
}
