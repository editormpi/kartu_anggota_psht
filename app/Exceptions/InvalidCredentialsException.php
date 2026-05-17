<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class InvalidCredentialsException extends RuntimeException
{
    public function __construct(string $message = 'NIK atau password salah')
    {
        parent::__construct($message);
    }
}
