<?php

namespace App\Models\Sdm;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\StatusKaryawan;
use App\Models\Surat\SuratCuti;
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

    public function masakerja(): Attribute
    {

        return Attribute::make(
            get: function () {
                $tglMasuk = Carbon::parse($this->tgl_masuk);
                $now = Carbon::now();

                if ($tglMasuk->greaterThan($now)) {
                    return 'Belum Masuk Kerja';
                }

                // set Different date
                $diff = $tglMasuk->diff($now);

                return  "{$diff->y} Tahun {$diff->m} Bulan ";
            }
        );
    }

    public function usia(): Attribute
    {
        return Attribute::make(
            // get: fn() => Carbon::parse($this->tgl_lahir)->diffInYears() . ' Tahun '
            get: function () {
                $tglLahir = Carbon::parse($this->tgl_lahir);
                $now = Carbon::now();

                $diff = $tglLahir->diff($now);

                return "{$diff->y} Tahun";
            }
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


    // get Jabatan latest / Saat Ini
    function jabatan()
    {
        return $this->belongsToMany(Jabatan::class, 'sdm_kary_jabatan', 'karyawan_id', 'jabatan_id')
            ->withPivot('id', 'created_at', 'tgl_mulai', 'tgl_berakhir')
            ->orderBy('pivot_created_at', 'desc')
            ->limit(1);
    }

    // Relation cuti
    public function suratCuti()
    {
        return $this->hasMany(SuratCuti::class, 'karyawan_id');
    }
}
