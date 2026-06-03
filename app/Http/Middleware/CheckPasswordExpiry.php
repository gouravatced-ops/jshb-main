<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            $passwordExpiryDays = config('panel.password_expiry_days', 30);

            if ($user->password_created_at) {
                $daysOld = $user->password_created_at->diffInDays(now());
                $isExpired = $daysOld >= $passwordExpiryDays;

                // Store in request for use in views
                $request->attributes->set('password_expired', $isExpired);
                $request->attributes->set('password_days_old', $daysOld);
                $request->attributes->set('password_expiry_days', $passwordExpiryDays);
            }
        }

        return $next($request);
    }
}
