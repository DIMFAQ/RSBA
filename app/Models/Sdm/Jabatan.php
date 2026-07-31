<?php

namespace App\Models\Sdm;

use App\Models\Sdm\Bagian;
use App\Models\Sdm\KaryawanJabatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jabatan extends Model
{
    protected $table = 'sdm_jabatan';
    protected $guarded = [];

    function atasan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'parent_id');
    }

    function bawahan(): HasMany
    {
        return $this->hasMany(Jabatan::class, 'parent_id');
    }

    // to get history jabatan
    function jabatans()
    {
        return $this->hasMany(KaryawanJabatan::class);
    }

    function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }

    function tingkat(): BelongsTo
    {
        return $this->belongsTo(JabatanTingkat::class, 'tingkat_id');
    }

    public function isKoordinator(): bool
    {
        return $this->tingkat?->is_penyusun_jadwal || $this->tingkat_id === 4;
    }

    public function isKepalaDept(): bool
    {
        return $this->tingkat_id === 3;
    }

    public function isWadir(): bool
    {
        return $this->tingkat_id === 2;
    }

    public static function getOrgChartNodes($bagianId = null): array
    {
        $query = static::with(['tingkat', 'bagian', 'jabatans.karyawan']);

        if ($bagianId) {
            $query->where('bagian_id', $bagianId);
        }

        $jabatans = $query->get();

        return $jabatans->map(function ($j) {
            $karyawanAktif = $j->jabatans->first(fn($kj) => $kj->is_active || is_null($kj->tgl_selesai))?->karyawan;
            $namaKaryawan = $karyawanAktif?->full_nama ?? 'Belum Ada Pejabat';

            return [
                'id' => (string) $j->id,
                'parentId' => $j->parent_id ? (string) $j->parent_id : null,
                'name' => $j->nama,
                'title' => $j->tingkat?->nama ?? ('Level ' . ($j->tingkat_id ?? '-')),
                'urutan' => $j->tingkat?->urutan ?? 99,
                'department' => $j->bagian?->nama ?? 'Umum RSBA',
                'employeeName' => $namaKaryawan,
                'hasEmployee' => (bool) $karyawanAktif,
            ];
        })->values()->toArray();
    }
}
