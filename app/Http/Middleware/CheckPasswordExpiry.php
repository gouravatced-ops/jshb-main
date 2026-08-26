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
     * @param  \Closure(Request): (\Symfony\Component\HttpFoundation\Response)  $next
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

                // Backend Security Enforcement: Block operations if expired
                if ($isExpired) {
                    $allowedRoutes = [
                        'password.check-expiry',
                        'password.update',
                        'password.captcha',
                        'logout'
                    ];

                    $routeName = $request->route() ? $request->route()->getName() : '';

                    if (!in_array($routeName, $allowedRoutes)) {
                        // Block any data modification (POST, PUT, DELETE, PATCH)
                        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('DELETE') || $request->isMethod('PATCH')) {
                            if ($request->ajax() || $request->wantsJson()) {
                                return response()->json(['error' => 'Password expired. Reset required.', 'expired' => true], 403);
                            }
                            return redirect()->back()->with('error', 'Your password has expired. You must reset it to continue operations.');
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}
