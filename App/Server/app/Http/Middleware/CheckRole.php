<?php

namespace App\Http\Middleware;

use App\Traits\APIResponse;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    use APIResponse;

    public function handle($request, Closure $next, ...$roles)
    {
        foreach ($roles as $role) {
            if (Auth::guard('admin_api')->check() && Auth::guard('admin_api')->user()->role === $role) {
                return $next($request);
            }
            if (Auth::guard('user_api')->check() && Auth::guard('user_api')->user()->role === $role) {
                return $next($request);
            }
        }

        return $this->responseError('Forbidden !', 403);
    }
}
