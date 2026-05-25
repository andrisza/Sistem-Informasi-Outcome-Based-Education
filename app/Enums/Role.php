<?php

namespace App\Enums;

enum Role: string
{
    case Kaprodi      = 'kaprodi';
    case TimKurikulum = 'tim_kurikulum';
    case Dosen        = 'dosen';
    case Mahasiswa    = 'mahasiswa';

    public function label(): string
    {
        return match($this) {
            self::Kaprodi      => 'Kepala Program Studi',
            self::TimKurikulum => 'Tim Kurikulum',
            self::Dosen        => 'Dosen',
            self::Mahasiswa    => 'Mahasiswa',
        };
    }

    public function dashboardRoute(): string
    {
        return match($this) {
            self::Kaprodi      => 'kaprodi.dashboard',
            self::TimKurikulum => 'kurikulum.dashboard',
            self::Dosen        => 'dosen.dashboard',
            self::Mahasiswa    => 'mahasiswa.dashboard',
        };
    }

    /** Peran yang boleh mengakses manajemen kurikulum */
    public function canManageKurikulum(): bool
    {
        return in_array($this, [self::Kaprodi, self::TimKurikulum]);
    }
}
