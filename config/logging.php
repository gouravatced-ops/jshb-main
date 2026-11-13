<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that is utilized to write
    | messages to your logs. The value provided here should match one of
    | the channels present in the list of "channels" configured below.
    |
    */

    'default' => env('LOG_CHANNEL', 'daily_folder'),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => env('LOG_DEPRECATIONS_TRACE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Laravel
    | utilizes the Monolog PHP logging library, which includes a variety
    | of powerful log handlers and formatters that you're free to use.
    |
    | Available drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog", "custom", "stack"
    |
    */

    'channels' => [

        'daily_folder' => [
            'driver' => 'custom',
            'via' => \App\Logging\DailyFolderLogger::class,
            'file' => 'laravel.log',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'stack' => [
            'driver' => 'stack',
            'channels' => explode(',', (string) env('LOG_STACK', 'daily_folder')),
            'ignore_exceptions' => false,
        ],

        'user_credentials' => [
            'driver' => 'custom',
            'via' => \App\Logging\DailyFolderLogger::class,
            'file' => 'user_credentials.log',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'single' => [
            'driver' => 'custom',
            'via' => \App\Logging\DailyFolderLogger::class,
            'file' => 'laravel.log',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'otp_dispatch' => [
            'driver' => 'custom',
            'via' => \App\Logging\DailyFolderLogger::class,
            'file' => 'otp_dispatch.log',
            'level' => 'info',
        ],

        'otp_sent' => [
            'driver' => 'custom',
            'via' => \App\Logging\DailyFolderLogger::class,
            'file' => 'otp_sent.log',
            'level' => 'info',
        ],

        'test_notification' => [
            'driver' => 'custom',
            'via' => \App\Logging\DailyFolderLogger::class,
            'file' => 'test_notification.log',
            'level' => 'debug',
        ],

        'notification_log' => [
            'driver' => 'custom',
            'via' => \App\Logging\DailyFolderLogger::class,
            'file' => 'notification.log',
            'level' => 'info',
        ],

        'send_mail' => [
            'driver' => 'custom',
            'via' => \App\Logging\DailyFolderLogger::class,
            'file' => 'send_mail.log',
            'level' => 'info',
        ],

        'sms' => [
            'driver' => 'custom',
            'via' => \App\Logging\DailyFolderLogger::class,
            'file' => 'sms.log',
            'level' => 'info',
        ],

        'whatsapp' => [
            'driver' => 'custom',
            'via' => \App\Logging\DailyFolderLogger::class,
            'file' => 'whatsapp.log',
            'level' => 'info',
        ],

        'daily' => [
            'driver' => 'custom',
            'via' => \App\Logging\DailyFolderLogger::class,
            'file' => 'laravel.log',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => env('LOG_SLACK_USERNAME', env('APP_NAME', 'Laravel')),
            'emoji' => env('LOG_SLACK_EMOJI', ':boom:'),
            'level' => env('LOG_LEVEL', 'critical'),
            'replace_placeholders' => true,
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class),
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
                'connectionString' => 'tls://'.env('PAPERTRAIL_URL').':'.env('PAPERTRAIL_PORT'),
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'handler_with' => [
                'stream' => 'php://stderr',
            ],
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
            'facility' => env('LOG_SYSLOG_FACILITY', LOG_USER),
            'replace_placeholders' => true,
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => getDailyLogPath('laravel.log'),
        ],

    ],

];
