<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $member = $request->user('member');

        if ($member !== null && $member->must_change_password) {
            if ($request->routeIs('password.change')) {
                return $next($request);
            }

            return redirect()->route('password.change')->with(
                'warning',
                'Silakan ubah password default Anda sebelum melanjutkan.'
            );
        }

        return $next($request);
    }
}
