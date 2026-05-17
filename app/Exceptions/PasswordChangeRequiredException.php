<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class PasswordChangeRequiredException extends RuntimeException
{
    public function __construct(string $message = 'Password harus diubah sebelum melanjutkan')
    {
        parent::__construct($message);
    }
}
