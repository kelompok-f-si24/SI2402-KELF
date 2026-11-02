<?php

namespace App\Enums;

enum UserRole: string
{
    use HasOptions;

    case JobSeeker = 'job_seeker';
    case Employer = 'employer';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::JobSeeker => 'Job Seeker',
            self::Employer => 'Employer',
            self::Admin => 'Admin',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::JobSeeker => 'sky',
            self::Employer => 'indigo',
            self::Admin => 'navy',
        };
    }

    /**
     * Roles that can be chosen on the public registration form.
     *
     * @return array<string, string>
     */
    public static function registrable(): array
    {
        return [
            self::JobSeeker->value => self::JobSeeker->label(),
            self::Employer->value => self::Employer->label(),
        ];
    }
}
