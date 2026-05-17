<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class TooManyAttemptsException extends RuntimeException
{
    public function __construct(string $message = 'Terlalu banyak percobaan. Coba lagi dalam 15 menit.')
    {
        parent::__construct($message);
    }
}
