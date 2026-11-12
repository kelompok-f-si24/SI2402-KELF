<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    use HasOptions;

    case Pending = 'pending';
    case Processing = 'processing';
    case Passed = 'passed';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Ditinjau',
            self::Processing => 'Diproses',
            self::Passed => 'Lolos Seleksi',
            self::Rejected => 'Ditolak',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'amber',
            self::Processing => 'sky',
            self::Passed => 'green',
            self::Rejected => 'red',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Pending => 'Lamaran Anda sudah terkirim dan menunggu ditinjau perusahaan.',
            self::Processing => 'Perusahaan sedang meninjau lamaran Anda.',
            self::Passed => 'Selamat! Anda lolos seleksi. Perusahaan akan menghubungi Anda.',
            self::Rejected => 'Maaf, lamaran Anda belum sesuai dengan kebutuhan perusahaan.',
        };
    }
}
