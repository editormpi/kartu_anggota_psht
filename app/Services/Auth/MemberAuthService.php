<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Exceptions\AccountInactiveException;
use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\TooManyAttemptsException;
use App\Models\Member;
use App\Support\NikEncryptor;
use App\Support\NikValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberAuthService
{
    public function __construct(
        private readonly NikValidator $nikValidator,
        private readonly NikEncryptor $nikEncryptor,
        private readonly LoginAttemptService $loginAttempts,
    ) {}

    /**
     * Authenticate a member by NIK and password.
     *
     * @throws InvalidCredentialsException When NIK is malformed, unknown, or password is wrong.
     * @throws AccountInactiveException When member exists but is_active is false.
     * @throws TooManyAttemptsException When the IP or NIK has exceeded its failure window.
     */
    public function attempt(string $nik, string $password, string $ipAddress, ?string $userAgent): Member
    {
        if (! $this->nikValidator->isValid($nik)) {
            // Use a dummy hash so we still rate-limit malformed input by IP.
            $nikHash = hash('sha256', 'invalid:'.$ipAddress);
            $this->loginAttempts->logFailure($nikHash, $ipAddress, $userAgent, 'malformed_nik');
            throw new InvalidCredentialsException;
        }

        $nikHash = $this->nikEncryptor->hash($nik);

        if ($this->loginAttempts->isThrottled($nikHash, $ipAddress)) {
            throw new TooManyAttemptsException;
        }

        /** @var ?Member $member */
        $member = Member::query()->where('nik_hash', $nikHash)->first();

        if ($member === null) {
            $this->loginAttempts->logFailure($nikHash, $ipAddress, $userAgent, 'unknown_nik');
            throw new InvalidCredentialsException;
        }

        if (! Hash::check($password, $member->password)) {
            $this->loginAttempts->logFailure($nikHash, $ipAddress, $userAgent, 'wrong_password');
            throw new InvalidCredentialsException;
        }

        if (! $member->is_active) {
            $this->loginAttempts->logFailure($nikHash, $ipAddress, $userAgent, 'inactive_account');
            throw new AccountInactiveException;
        }

        $this->loginAttempts->logSuccess($nikHash, $ipAddress, $userAgent);

        $member->forceFill(['last_login_at' => now()])->save();

        Auth::guard('member')->login($member);

        return $member;
    }

    public function logout(): void
    {
        Auth::guard('member')->logout();
    }
}
