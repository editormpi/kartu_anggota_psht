<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use RuntimeException;

class NikEncryptor
{
    public function hash(string $nik): string
    {
        return hash('sha256', $nik);
    }

    public function encrypt(string $nik): string
    {
        return Crypt::encryptString($nik);
    }

    /**
     * @throws RuntimeException when ciphertext cannot be decrypted.
     */
    public function decrypt(string $ciphertext): string
    {
        try {
            return Crypt::decryptString($ciphertext);
        } catch (DecryptException $e) {
            throw new RuntimeException('Unable to decrypt NIK ciphertext.', 0, $e);
        }
    }

    public function mask(string $nik): string
    {
        if (strlen($nik) < 8) {
            return str_repeat('*', strlen($nik));
        }

        return substr($nik, 0, 4).str_repeat('*', max(0, strlen($nik) - 8)).substr($nik, -4);
    }
}
