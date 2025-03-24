<?php

namespace App\Enums;

enum StatusKaryawan: string
{
    case TETAP = 'tetap';
    case KONTRAK = 'kontrak';
    case MITRA = 'mitra';
    case BANTUAN = 'bantuan';
    case MAGANG = 'magang';

    public function nama(): string
    {
        return match ($this) {
            self::TETAP => 'Pegawai Tetap',
            self::KONTRAK => 'Pegawai Kontrak',
            self::MITRA => 'Mitra / Tamu',
            self::BANTUAN => 'Perbantuan',
            self::MAGANG => 'Magang / Interns',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::TETAP => 'success',
            self::KONTRAK => 'info',
            self::MITRA => 'info',
            self::BANTUAN => 'warning',
            self::MAGANG => 'danger'
        };
    }

    public function idNIP(): int
    {
        return match ($this) {
            self::TETAP => 1,
            self::KONTRAK => 2,
            self::MITRA => 3,
            self::BANTUAN => 4,
            self::MAGANG => 5
        };
    }


    public static function options(): array
    {
        return array_map(
            fn($status) => [
                'value' => $status->value,
                'label' => $status->nama()
            ],
            self::cases()
        );
    }
}
