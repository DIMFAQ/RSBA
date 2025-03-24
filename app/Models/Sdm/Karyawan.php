<?php

namespace App\Models\Sdm;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\StatusKaryawan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Karyawan extends Model
{
    protected $table = 'sdm_karyawan';
    protected $guarded = [];

    // casting enum status karyawan
    protected $casts = [
        'status' => StatusKaryawan::class
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'karyawan_id');
    }

    public function masaKerja(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->tgl_masuk)->diffInYears() . ' Tahun ' .
                Carbon::parse($this->tgl_masuk)->diffInMonths() % 12 . ' Bulan ' .
                Carbon::parse($this->tgl_masuk)->diffInDays() % 30 . ' Hari'
        );
    }

    public function usia(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->tgl_lahir)->diffInYears() . ' Tahun '
        );
    }

    function latestJabatan()
    {
        return $this->hasOne(KaryawanJabatan::class, 'karyawan_id')
            ->latest('created_at')
            ->with('jabatan');
    }

    // Get History Jabatan
    function historyJabatan()
    {
        return $this->belongsToMany(Jabatan::class, KaryawanJabatan::class)
            ->withPivot('id', 'created_at', 'tgl_mulai', 'tgl_berakhir')
            ->orderBy('pivot_created_at', 'desc');
    }
}
