<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:send-application-due-reminders')->dailyAt('06:00');
Schedule::command('app:auto-escalate-stalled-applications --days=3')->dailyAt('06:05');

// Document Generation Batch Process
Schedule::command('app:process-document-generation-queue --portion=half')->dailyAt('00:00');
Schedule::command('app:process-document-generation-queue --portion=all')->dailyAt('06:00');
