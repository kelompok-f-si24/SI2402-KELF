<?php

namespace App\Enums;

enum SkillLevel: string
{
    use HasOptions;

    case Beginner = 'beginner';
    case Intermediate = 'intermediate';
    case Advanced = 'advanced';
    case Expert = 'expert';

    public function label(): string
    {
        return match ($this) {
            self::Beginner => 'Pemula',
            self::Intermediate => 'Menengah',
            self::Advanced => 'Mahir',
            self::Expert => 'Ahli',
        };
    }

    public function percentage(): int
    {
        return match ($this) {
            self::Beginner => 25,
            self::Intermediate => 50,
            self::Advanced => 75,
            self::Expert => 100,
        };
    }
}
