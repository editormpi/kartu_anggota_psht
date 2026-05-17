<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Services\Member\MemberProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use InvalidArgumentException;

class ChangePasswordController extends Controller
{
    public function __construct(private readonly MemberProfileService $profile) {}

    public function show(): View
    {
        return view('auth.change-password');
    }

    public function store(ChangePasswordRequest $request): RedirectResponse
    {
        $member = $request->user('member');

        try {
            $this->profile->changePassword(
                $member,
                (string) $request->validated('current_password'),
                (string) $request->validated('new_password'),
            );
        } catch (InvalidArgumentException $e) {
            return back()->withErrors(['current_password' => $e->getMessage()]);
        }

        return redirect()->route('portal.dashboard')->with(
            'success',
            'Password berhasil diubah.'
        );
    }
}
