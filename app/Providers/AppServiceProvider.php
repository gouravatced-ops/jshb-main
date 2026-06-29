<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::define('super-admin', function (\App\Models\User $user) {
            return $user->roleRelation?->slug === 'super-admin';
        });
    }
}
