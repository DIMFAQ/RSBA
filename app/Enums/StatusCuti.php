<?php

namespace App\Enums;

enum StatusCuti: string
{
    case PROSES = 'proses';
    case DISETUJUI = 'disetujui';
    case DITOLAK = 'ditolak';

    function nama(): string
    {
        return match ($this) {
            self::PROSES => 'Proses',
            self::DISETUJUI => 'Disetujui',
            self::DITOLAK => 'Ditolak'
        };
    }

    function options(): array
    {
        return array_map(
            fn($status) => [
                'value' => $status->value,
                'label' => $status->nama()
            ],
            self::cases()
        );
    }


    function color(): string
    {
        return match ($this) {
            self::PROSES => 'warning',
            self::DISETUJUI => 'success',
            self::DITOLAK => 'danger'
        };
    }
}
