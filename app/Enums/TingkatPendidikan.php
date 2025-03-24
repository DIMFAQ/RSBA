<?php

namespace App\Enums;

enum TingkatPendidikan: string
{
    case SD = 'sd';
    case SMP = 'smp';
    case SMA = 'sma';
    case D3 = 'd3';
    case D4 = 'd4';
    case S1 = 's1';
    case S2 = 's2';
    case S3 = 's3';
    case DOKTER = 'dokter';
    case SPESIALIS = 'spesialis';
    case PROFESI = 'profesi';
    case LAIN = 'lain';


    public function nama(): string
    {
        return match ($this) {
            self::SD => 'SD',
            self::SMP  => 'SMP',
            self::SMA  => 'SMA',
            self::D3  => 'D3',
            self::D4  => 'D4',
            self::S1  => 'S1 Sarjana',
            self::S2  => 'S2 Magister',
            self::S3  =>  'S3 Doktor',
            self::DOKTER  =>  'DOKTER',
            self::SPESIALIS  =>  'SPESIALIS',
            self::PROFESI  =>  'PROFESI',
            self::LAIN  =>  'Pendidikan Lain',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn($status) => [
                'value' => $status->value,
                'label' => $status->nama(),
            ],
            self::cases()
        );
    }
}
