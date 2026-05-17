<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $nik_hash
 * @property string $ip_address
 * @property ?string $user_agent
 * @property bool $successful
 * @property ?string $failure_reason
 * @property Carbon $attempted_at
 */
class LoginAttempt extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'nik_hash',
        'ip_address',
        'user_agent',
        'successful',
        'failure_reason',
        'attempted_at',
    ];

    protected function casts(): array
    {
        return [
            'successful' => 'boolean',
            'attempted_at' => 'datetime',
        ];
    }
}
