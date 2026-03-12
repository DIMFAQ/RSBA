<?php

namespace App\Models\Surat;

use App\Enums\StatusApproval;
use App\Models\Sdm\Karyawan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SuratCuti extends Model
{
    protected $table = 'surat_cuti';
    protected $guarded = [];

    protected $casts = [
        'status' => StatusApproval::class
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }

    public function created_oleh()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // accessor user_created
    public function getUserCreatedAttribute(): string
    {
        if ($this->relationLoaded('created_oleh') && $this->created_oleh?->relationLoaded('karyawan')) {
            return $this->created_oleh?->karyawan->nama ?? '-';
        }

        return optional(optional($this->created_oleh)?->karyawan)?->nama ?? '-';
    }

    public function jenis()
    {
        return $this->belongsTo(CutiJenis::class, 'urgensi_id', 'id');
    }

    public function approvals()
    {
        return $this->hasMany(SuratCutiApproval::class, 'surat_cuti_id', 'id');
    }
}
