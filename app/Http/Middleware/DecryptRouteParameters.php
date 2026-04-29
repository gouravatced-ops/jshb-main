<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DecryptRouteParameters
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Decrypt route parameters if needed
        $route = $request->route();
        
        if ($route) {
            $parameters = $route->parameters();
            foreach ($parameters as $key => $value) {
                if (is_string($value) && strlen($value) > 20) {
                    $decrypted = decryptId($value);
                    if ($decrypted !== null) {
                        $route->setParameter($key, $decrypted);
                    }
                }
            }
        }

        return $next($request);
    }
}
