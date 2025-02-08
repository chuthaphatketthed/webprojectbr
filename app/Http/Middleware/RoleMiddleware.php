<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่ และตรวจสอบบทบาท (role)
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // หากไม่ผ่านการตรวจสอบ ให้ส่งกลับหน้า Unauthorized หรืออื่น ๆ
        abort(403, 'คุณไม่ได้รับสิทธิ์เข้าถึงหน้านี้');
    }
}
