<?php

namespace App\Enums;

enum Agama: string
{

    case ISLAM = 'islam';
    case KRISTEN = 'kristen';
    case KATOLIK = 'katolik';
    case HINDU = 'hindu';
    case BUDHA = 'budha';
    case KHONGHUCU = 'khonghucu';


    public function nama(): string
    {

        return match ($this) {
            self::ISLAM => 'Islam',
            self::KRISTEN => 'Kristen',
            self::KATOLIK => 'Katolik',
            self::HINDU => 'Hindu',
            self::BUDHA => 'Budha',
            self::KHONGHUCU => 'Khonghucu',
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
