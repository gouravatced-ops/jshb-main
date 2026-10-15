<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobFailed;
use App\Models\User;
use App\Models\Notification;
use App\Models\AllotteeNotification;
use App\Events\AllotteeCreated;
use App\Listeners\SendAllotteeCredentials;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Failed;
use App\Listeners\LogSuccessfulLogin;
use App\Listeners\LogFailedLogin;
use Illuminate\Support\Facades\Event;

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

        // Register Events
        Event::listen(AllotteeCreated::class, SendAllotteeCredentials::class);

        // ----------------------------------------------------
        // MODULE-BASED GATES (Using User Model Attributes)
        // ----------------------------------------------------

        Gate::define('manage-allottees', function (User $user) {
            // Allow only administration (admin, super-admin)
            return $user->user_type === 'administration';
        });

        Gate::define('manage-transactions', function (User $user) {
            return $user->user_type === 'administration' || $user->user_type === 'accountant';
        });

        Gate::define('manage-components', function (User $user) {
            return $user->user_type === 'administration';
        });

        Gate::define('manage-applications', function (User $user) {
            return in_array($user->user_type, ['administration', 'engineer', 'operator', 'coassistant']);
        });

        Gate::define('manage-workflows', function (User $user) {
            return $user->user_type === 'administration';
        });

        Gate::define('view-reports', function (User $user) {
            return in_array($user->user_type, ['administration', 'engineer', 'division', 'managing']);
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

        // Global Queue Worker Debug Logging
        Queue::before(function (JobProcessing $event) {
            $msg = "[".now()->toDateTimeString()."] [START] Job: " . $event->job->resolveName() . " (Attempts: " . $event->job->attempts() . ")" . PHP_EOL;
            file_put_contents(storage_path('logs/queue_worker_debug.log'), $msg, FILE_APPEND);
        });

        Queue::after(function (JobProcessed $event) {
            $msg = "[".now()->toDateTimeString()."] [SUCCESS] Job: " . $event->job->resolveName() . PHP_EOL;
            file_put_contents(storage_path('logs/queue_worker_debug.log'), $msg, FILE_APPEND);
        });

        Queue::failing(function (JobFailed $event) {
            $msg = "[".now()->toDateTimeString()."] [FAILED] Job: " . $event->job->resolveName() . PHP_EOL;
            $msg .= " - Error Message: " . $event->exception->getMessage() . PHP_EOL;
            $msg .= " - File: " . $event->exception->getFile() . " on line " . $event->exception->getLine() . PHP_EOL;
            file_put_contents(storage_path('logs/queue_worker_debug.log'), $msg, FILE_APPEND);
        });
    }
}
