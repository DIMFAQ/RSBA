<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Sdm\Karyawan;
use App\Models\Surat\SuratSp3Approval;
use App\Models\Sdm\RuanganKoordinator;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    use HasRoles;

    protected $fillable = [
        'email',
        'password',
        'karyawan_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function certificate(): HasMany
    {
        return $this->hasMany(SignatureCerts::class, 'user_id', 'id');
    }

    public function approval(): HasMany
    {
        return $this->hasMany(SuratSp3Approval::class, 'disetujui', 'id');
    }

    public function koordinatorRuangans(): HasMany
    {
        return $this->hasMany(RuanganKoordinator::class, 'user_id')->where('aktif', true);
    }

    public function isKoordinator(): bool
    {
        if ($this->hasRole(['Super-Admin', 'Staff-SDM'])) {
            return true;
        }
        try {
            return $this->koordinatorRuangans()->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getRuanganKoordinatorIds(): ?array
    {
        if ($this->hasRole(['Super-Admin', 'Staff-SDM'])) {
            return null;
        }
        try {
            return $this->koordinatorRuangans()->pluck('ruangan_id')->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function isDokterOrApprover(): bool
    {
        if ($this->hasRole(['Super-Admin', 'Wakil-Direktur', 'Staff-SDM']) || $this->can('approve-jadwal-wadir')) {
            return true;
        }
        if ($this->hasRole(['Koordinator-Dokter', 'Dokter'])) {
            return true;
        }
        if (!$this->karyawan_id) {
            return false;
        }
        try {
            if (\Illuminate\Support\Facades\DB::table('dokter')->where('karyawan_id', $this->karyawan_id)->exists()) {
                return true;
            }
        } catch (\Throwable $e) {}
        $karyawan = $this->karyawan;
        if ($karyawan && (str_contains(strtolower($karyawan->gelar_depan ?? ''), 'dr') || str_contains(strtolower($karyawan->gelar_belakang ?? ''), 'sp'))) {
            return true;
        }
        return false;
    }

    public function isDokter(): bool
    {
        if (!$this->karyawan_id) {
            return false;
        }
        try {
            return \App\Models\Sdm\Dokter::where('karyawan_id', $this->karyawan_id)->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function isKoordinatorDokter(): bool
    {
        if ($this->hasRole(['Super-Admin', 'Staff-SDM'])) {
            return false;
        }
        return $this->isKoordinator() && $this->isDokter();
    }

    public function isKoordinatorKaryawan(): bool
    {
        if ($this->hasRole(['Super-Admin', 'Staff-SDM'])) {
            return false;
        }
        return $this->isKoordinator() && !$this->isDokter();
    }
}