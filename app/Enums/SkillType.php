<?php

namespace App\Enums;

enum SkillType: string
{
    use HasOptions;

    case Hard = 'hard';
    case Soft = 'soft';

    public function label(): string
    {
        return match ($this) {
            self::Hard => 'Hard Skill',
            self::Soft => 'Soft Skill',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Hard => 'indigo',
            self::Soft => 'sky',
        };
    }
}
