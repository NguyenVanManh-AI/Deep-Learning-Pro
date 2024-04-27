<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // Authenticate này của hệ thống nên khó tùy biến => tạo file mới CheckAuthenticate.php

        // có nhiều cách
        // Cách 1 : tất cả đều cho về trang 401
        // Cách 2 : hoặc tùy vào guard là user hay amdin ,... để cho về trang hoặc xử lí theo ý mình

        // Cách 1 :
        // request web
        if (! $request->expectsJson()) {
            return route('errors.401');
        }

        // request api
        // LỖI
        // return response()->json(['error' => 'Unauthenticated.'], 401);

        // XEM Ở CheckAuthenticate.php
        // Cách 2 :
        // ở đây có session : admin , user
        // jwt : admin_api, user_api
    }
}
