<?php

namespace App\Enums;

enum JobCategory: string
{
    use HasOptions;

    case Technology = 'technology';
    case Design = 'design';
    case Marketing = 'marketing';
    case Finance = 'finance';
    case Sales = 'sales';
    case HumanResources = 'human_resources';
    case Operations = 'operations';
    case Education = 'education';
    case Health = 'health';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Technology => 'Teknologi & IT',
            self::Design => 'Desain & Kreatif',
            self::Marketing => 'Marketing',
            self::Finance => 'Keuangan & Akuntansi',
            self::Sales => 'Penjualan',
            self::HumanResources => 'SDM & HR',
            self::Operations => 'Operasional',
            self::Education => 'Pendidikan',
            self::Health => 'Kesehatan',
            self::Other => 'Lainnya',
        };
    }
}
