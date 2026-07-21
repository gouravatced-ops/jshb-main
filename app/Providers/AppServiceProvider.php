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

        // Inject notifications into header
        \Illuminate\Support\Facades\View::composer('components.header', function ($view) {
            if (auth()->check()) {
                $notifications = \App\Models\Notification::where('user_id', auth()->id())
                                    ->orderBy('created_at', 'desc')
                                    ->take(10)
                                    ->get();
                $unreadCount = $notifications->where('is_read', 0)->count();
                $view->with('headerNotifications', $notifications)->with('unreadNotifCount', $unreadCount);
            }
        });
    }
}
