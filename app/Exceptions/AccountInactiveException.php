<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class AccountInactiveException extends RuntimeException
{
    public function __construct(string $message = 'Akun belum aktif, hubungi admin')
    {
        parent::__construct($message);
    }
}
