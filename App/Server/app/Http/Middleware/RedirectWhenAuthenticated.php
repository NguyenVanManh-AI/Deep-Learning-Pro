<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectWhenAuthenticated
{
    public function handle($request, Closure $next, $guard = null)
    {
        if ($guard === 'user' && Auth::guard($guard)->check()) {
            return redirect()->route('user.account.infor');
        }

        if ($guard === 'admin' && Auth::guard($guard)->check()) {
            return redirect()->route('admin.account.infor');
        }

        return $next($request);
    }
}
