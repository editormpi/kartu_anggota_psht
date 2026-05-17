<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\AccountInactiveException;
use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\TooManyAttemptsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\MemberAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(private readonly MemberAuthService $auth) {}

    public function show(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $member = $this->auth->attempt(
                (string) $request->validated('nik'),
                (string) $request->validated('password'),
                (string) $request->ip(),
                $request->userAgent(),
            );
        } catch (TooManyAttemptsException $e) {
            return back()
                ->withInput($request->only('nik'))
                ->withErrors(['nik' => $e->getMessage()]);
        } catch (AccountInactiveException $e) {
            return back()
                ->withInput($request->only('nik'))
                ->withErrors(['nik' => $e->getMessage()]);
        } catch (InvalidCredentialsException $e) {
            return back()
                ->withInput($request->only('nik'))
                ->withErrors(['nik' => $e->getMessage()]);
        }

        RateLimiter::clear('login:'.$request->ip());
        $request->session()->regenerate();

        if ($member->must_change_password) {
            return redirect()->route('password.change');
        }

        return redirect()->intended(route('portal.dashboard'));
    }
}
