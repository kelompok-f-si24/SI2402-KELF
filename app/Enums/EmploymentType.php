<?php

namespace App\Enums;

enum EmploymentType: string
{
    use HasOptions;

    case FullTime = 'full_time';
    case PartTime = 'part_time';
    case Contract = 'contract';
    case Internship = 'internship';
    case Freelance = 'freelance';

    public function label(): string
    {
        return match ($this) {
            self::FullTime => 'Full Time',
            self::PartTime => 'Part Time',
            self::Contract => 'Kontrak',
            self::Internship => 'Magang',
            self::Freelance => 'Freelance',
        };
    }
}
