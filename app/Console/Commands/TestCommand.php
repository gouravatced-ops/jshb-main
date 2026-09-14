<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test command to verify server execution';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $time = now()->toDateTimeString();
        $message = "[{$time}] Hello World - The server cron job is working perfectly!" . PHP_EOL;

        // Save to storage/logs/test_command.log
        file_put_contents(storage_path('logs/test_command.log'), $message, FILE_APPEND);

        $this->info('Test log written successfully.');
    }
}
