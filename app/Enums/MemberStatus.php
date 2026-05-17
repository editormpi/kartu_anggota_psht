<?php

declare(strict_types=1);

namespace App\Enums;

enum MemberStatus: string
{
    case AKTIF = 'Aktif';
    case TIDAK_AKTIF = 'Tidak Aktif';
    case ALUMNI = 'Alumni';
    case BERHENTI = 'Berhenti';

    public function label(): string
    {
        return $this->value;
    }
}
