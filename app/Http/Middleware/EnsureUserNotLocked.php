<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserNotLocked
{
    /**
     * Nếu user đã đăng nhập nhưng bị khóa → đăng xuất và chuyển về trang login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Refresh từ DB để lấy trạng thái locked_at mới nhất (admin vừa khóa)
            $user->refresh();
            if ($user->isLocked()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Tài khoản đã bị khóa. Vui lòng liên hệ quản trị viên.',
                ]);
            }
        }

        return $next($request);
    }
}
