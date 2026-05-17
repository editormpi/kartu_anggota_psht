<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\LoginAttempt;
use Illuminate\Support\Carbon;

class LoginAttemptService
{
    public const MAX_FAILURES_PER_IP = 5;

    public const FAILURE_WINDOW_IP_MINUTES = 15;

    public const MAX_FAILURES_PER_NIK = 10;

    public const FAILURE_WINDOW_NIK_MINUTES = 60;

    public function isThrottled(string $nikHash, string $ipAddress): bool
    {
        $ipFailures = LoginAttempt::query()
            ->where('ip_address', $ipAddress)
            ->where('successful', false)
            ->where('attempted_at', '>=', Carbon::now()->subMinutes(self::FAILURE_WINDOW_IP_MINUTES))
            ->count();

        if ($ipFailures >= self::MAX_FAILURES_PER_IP) {
            return true;
        }

        $nikFailures = LoginAttempt::query()
            ->where('nik_hash', $nikHash)
            ->where('successful', false)
            ->where('attempted_at', '>=', Carbon::now()->subMinutes(self::FAILURE_WINDOW_NIK_MINUTES))
            ->count();

        return $nikFailures >= self::MAX_FAILURES_PER_NIK;
    }

    public function logSuccess(string $nikHash, string $ipAddress, ?string $userAgent): void
    {
        LoginAttempt::query()->create([
            'nik_hash' => $nikHash,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'successful' => true,
            'failure_reason' => null,
            'attempted_at' => Carbon::now(),
        ]);
    }

    public function logFailure(string $nikHash, string $ipAddress, ?string $userAgent, string $reason): void
    {
        LoginAttempt::query()->create([
            'nik_hash' => $nikHash,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'successful' => false,
            'failure_reason' => $reason,
            'attempted_at' => Carbon::now(),
        ]);
    }
}
