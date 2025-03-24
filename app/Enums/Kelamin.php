<?php

namespace App\Enums;

enum Kelamin: string
{

    case LK = 'L';
    case PR = 'P';

    public function nama(): string
    {
        return match ($this) {
            self::LK => 'Laki - Laki',
            self::PR => 'Perempuan'
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

    public function icons(): string
    {
        return match ($this) {
            self::LK => 'gender-male',
            self::PR => 'gender-female'
        };
    }

    public function colors(): string
    {
        return match ($this) {
            self::LK => 'blue',
            self::PR => 'pink'
        };
    }
}
