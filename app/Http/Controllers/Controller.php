<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    protected function redirectIfLocked()
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        if ($user->is_locked) {
            $route = request()->route()?->getName();
            $allowedRoutes = [
                'lock.screen',
                'lock.unlock',
                'lock.lock',
                'logout',
            ];

            if (! in_array($route, $allowedRoutes, true)) {
                return redirect()->route('lock.screen');
            }
        }

        return null;
    }
}
