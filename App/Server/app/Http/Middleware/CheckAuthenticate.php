<?php

namespace App\Http\Middleware;

use App\Traits\APIResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuthenticate
{
    use APIResponse;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function checkUserBlockAndDelete($guard)
    {
        $user = Auth::guard('user_api')->user();
        if ($user->is_block == 1 || $user->is_delete == 1) {
            return true;
        }
    }

    public function handle(Request $request, Closure $next, ...$guards)
    {
        /*
            Dành cho trường hợp middleware có nhiều guard , ví dụ :
            Route::middleware('check.auth:admin_api,user_api')->group(function () {
            Route::middleware('check.auth:admin,user')->group(function () {

            TH1 : Một Guard , ví dụ : check.auth:user thì không có user (user chưa login) thì tới vòng lặp for tiếp theo để thông báo 401 hoặc trỏ đến trang login
            TH2 : Nhiều Guard , cũng tương tự , ví dụ : check.auth:user,admin => lặp qua hết nếu không có cái nào thõa mãn thì chuyển đến for tiếp theo để
            thôn báo 401 hoặc chuyển trến trang login
        */
        /*
            Bình thường sẽ không có dòng này , nhưng nếu muốn check thêm các trường hợp user bị block hay delete thì dùng
            if($guard == 'user_api') if($this->checkUserBlockAndDelete($guard)) continue;
        */

        // Lặp qua hết tất cả các guard
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard == 'user_api') {
                    if ($this->checkUserBlockAndDelete($guard)) {
                        continue;
                    }
                }

                return $next($request);
            }
        }

        // Thông báo 401 hoặc trỏ đến trang cần thiết
        foreach ($guards as $guard) {
            if ($guard == 'user') {
                // return redirect()->route('errors.401');
                return redirect()->route('user.login.view');
            }

            if ($guard == 'admin') {
                // return redirect()->route('errors.401');
                return redirect()->route('admin.login.view');
            }

            if ($guard == 'admin_api') {
                return $this->responseError('Unauthenticated !', 401);
                // return response()->json(['error' => 'Unauthenticated.'], 401);
                // ở đây json ta có thể trả về tùy ý và thêm nhiều param khác nữa
            }

            if ($guard == 'user_api') {
                return $this->responseError('Unauthenticated !', 401);
                // return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        }
    }
}
