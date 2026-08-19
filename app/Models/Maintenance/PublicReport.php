<?php

namespace App\Models\Maintenance;

use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PublicReport extends Model
{
    protected $table = 'public_maintc_reports';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static function booted(): void
    {
        static::creating(function (PublicReport $report) {
            if (empty($report->tracking_code)) {
                do {
                    $code = 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(6));
                } while (static::where('tracking_code', $code)->exists());

                $report->tracking_code = $code;
            }
        });
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id', 'id');
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by', 'id');
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {
            'it'    => 'IT',
            default => 'Umum',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'proses'  => 'Diproses',
            'selesai' => 'Selesai',
            default   => 'Pending',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'proses'  => 'yellow',
            'selesai' => 'green',
            default   => 'red',
        };
    }
}
