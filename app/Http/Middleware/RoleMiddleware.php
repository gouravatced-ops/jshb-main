<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Permission denied.');
        }

        if (! in_array($user->role, $roles, true)) {
            $dashboardRoute = $user->role === 'admin' ? 'admin.dashboard' : 'dashboard';

            return redirect()
                ->route($dashboardRoute)
                ->with('error', 'You do not have permission to access that page.');
        }

        return $next($request);
    }
}
