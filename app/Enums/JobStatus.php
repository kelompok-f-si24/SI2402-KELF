<?php

namespace App\Enums;

enum JobStatus: string
{
    use HasOptions;

    case Active = 'active';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Aktif',
            self::Closed => 'Ditutup',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'green',
            self::Closed => 'gray',
        };
    }
}
