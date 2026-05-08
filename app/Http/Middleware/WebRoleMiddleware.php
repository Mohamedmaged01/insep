<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WebRoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
        }

        // super_admin bypasses all role restrictions
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
        }

        return $next($request);
    }
}
