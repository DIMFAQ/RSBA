<?php

namespace App\Enums;

enum StatusApproval: string
{
    case PENDING = 'pending';
    case WAITING = 'waiting';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function nama(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::WAITING => 'Menunggu',
            self::APPROVED => 'Disetujui',
            self::REJECTED => 'Tidak Disetujui',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::WAITING => 'info',
            self::APPROVED => 'success',
            self::REJECTED => 'danger'
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
