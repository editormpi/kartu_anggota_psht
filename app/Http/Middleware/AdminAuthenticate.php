<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->guard('web')->check()) {
            return redirect()->route('admin.login');
        }

        if (! auth()->guard('web')->user()->is_admin) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
