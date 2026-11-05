<?php

namespace App\Enums;

enum EducationLevel: string
{
    use HasOptions;

    case Sma = 'sma';
    case D3 = 'd3';
    case D4 = 'd4';
    case S1 = 's1';
    case S2 = 's2';
    case S3 = 's3';

    public function label(): string
    {
        return match ($this) {
            self::Sma => 'SMA/SMK',
            self::D3 => 'D3',
            self::D4 => 'D4',
            self::S1 => 'S1',
            self::S2 => 'S2',
            self::S3 => 'S3',
        };
    }
}
