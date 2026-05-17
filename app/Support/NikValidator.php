<?php

declare(strict_types=1);

namespace App\Support;

class NikValidator
{
    public const LENGTH = 16;

    public function isValid(string $nik): bool
    {
        return $this->isWellFormed($nik);
    }

    public function isWellFormed(string $nik): bool
    {
        return preg_match('/^\d{'.self::LENGTH.'}$/', $nik) === 1;
    }

    public function normalize(string $nik): string
    {
        return preg_replace('/\D/', '', $nik) ?? '';
    }
}
