<?php

declare(strict_types=1);

namespace App\Services\Member;

use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class MemberProfileService
{
    /**
     * @throws InvalidArgumentException when the new password matches NIK or date-of-birth heuristics.
     */
    public function changePassword(Member $member, string $currentPassword, string $newPassword): void
    {
        if (! Hash::check($currentPassword, $member->password)) {
            throw new InvalidArgumentException('Password saat ini salah');
        }

        $forbidden = [];

        if ($member->tanggal_lahir !== null) {
            $forbidden[] = $member->tanggal_lahir->format('dmY');
            $forbidden[] = $member->tanggal_lahir->format('Ymd');
        }

        $forbidden[] = $member->getNik();

        if (in_array($newPassword, $forbidden, true)) {
            throw new InvalidArgumentException('Password baru tidak boleh sama dengan NIK atau tanggal lahir');
        }

        $member->forceFill([
            'password' => $newPassword,
            'must_change_password' => false,
        ])->save();
    }
}
