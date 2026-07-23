<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Notification;
use App\Models\AllotteeNotification;

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
        Gate::define('super-admin', function (User $user) {
            return $user->roleRelation?->slug === 'super-admin';
        });

        // Inject notifications into header and sidebar components
        View::composer(['components.header', 'components.partials.common-sidebar-elements'], function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                $isAllottee = ($user->user_type === 'allottee' || $user->getConnectionName() === 'adms_allottees');
                $model = $isAllottee ? AllotteeNotification::class : Notification::class;

                $notifications = $model::where('user_id', $user->id)
                                    ->orderBy('created_at', 'desc')
                                    ->take(10)
                                    ->get();
                $unreadCount = $model::where('user_id', $user->id)->where('is_read', 0)->count();
                $view->with('headerNotifications', $notifications)->with('unreadNotifCount', $unreadCount);
            }
        });
    }
}
