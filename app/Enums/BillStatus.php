<?php

declare(strict_types=1);

namespace App\Enums;

enum BillStatus: string
{
    case LUNAS = 'Lunas';
    case BELUM_LUNAS = 'Belum Lunas';
    case SEBAGIAN = 'Sebagian';

    public function label(): string
    {
        return $this->value;
    }

    public static function fromAmounts(int $nominal, int $dibayar): self
    {
        if ($dibayar <= 0) {
            return self::BELUM_LUNAS;
        }

        if ($dibayar >= $nominal) {
            return self::LUNAS;
        }

        return self::SEBAGIAN;
    }
}
