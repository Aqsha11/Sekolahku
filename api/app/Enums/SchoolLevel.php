<?php

namespace App\Enums;

enum SchoolLevel: string
{
    case SD = 'SD';
    case MI = 'MI';
    case SMP = 'SMP';
    case MTS = 'MTS';
    case SMA = 'SMA';
    case MA = 'MA';
    case SMK = 'SMK';

    public function label(): string
    {
        return match ($this) {
            self::SD => 'Sekolah Dasar',
            self::MI => 'Madrasah Ibtidaiyah',
            self::SMP => 'Sekolah Menengah Pertama',
            self::MTS => 'Madrasah Tsanawiyah',
            self::SMA => 'Sekolah Menengah Atas',
            self::MA => 'Madrasah Aliyah',
            self::SMK => 'Sekolah Menengah Kejuruan',
        };
    }
}