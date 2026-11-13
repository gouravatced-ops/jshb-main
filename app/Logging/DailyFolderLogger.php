<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\LogRecord;
use Monolog\Level;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\IntrospectionProcessor;

class DailyFolderHandler extends AbstractProcessingHandler
{
    protected string $fileName;

    public function __construct(string $fileName = 'laravel.log', int|string|Level $level = Level::Debug, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->fileName = $fileName;
    }

    protected function write(LogRecord $record): void
    {
        $today = now()->format('Y-m-d');
        $dir = storage_path("logs/{$today}");

        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        $formatted = (string) $record->formatted;

        // Write directly to storage/logs/{today}/{fileName}
        file_put_contents("{$dir}/{$this->fileName}", $formatted, FILE_APPEND | LOCK_EX);
    }
}

class DailyFolderLogger
{
    /**
     * Create a custom Monolog instance that dynamically routes all Log:: calls
     * to storage/logs/{todaydate}/{filename}
     *
     * @param array $config
     * @return Logger
     */
    public function __invoke(array $config): Logger
    {
        $channelName = $config['name'] ?? 'daily_folder';
        $fileName = $config['file'] ?? ($config['filename'] ?? 'laravel.log');
        $logger = new Logger($channelName);

        $levelName = $config['level'] ?? env('LOG_LEVEL', 'debug');
        $level = Level::fromName($levelName);

        $handler = new DailyFolderHandler($fileName, $level);

        // Formatter with multiline exceptions and stack traces enabled
        $formatter = new LineFormatter(
            "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
            "Y-m-d H:i:s",
            true, // Allow multiline
            true  // Discard empty extra
        );
        $formatter->includeStacktraces(true);
        $handler->setFormatter($formatter);

        // Push handler
        $logger->pushHandler($handler);

        // Add file and line introspection processor
        $logger->pushProcessor(new IntrospectionProcessor($level, [
            'Illuminate\\',
            'Monolog\\',
        ]));

        // Add contextual request metadata processor
        $logger->pushProcessor(function (LogRecord $record) {
            $extra = $record->extra;
            if (function_exists('request') && request()) {
                $extra['url'] = request()->method() . ' ' . request()->fullUrl();
                $extra['ip'] = request()->ip();
                if (function_exists('auth') && auth()->check()) {
                    $u = auth()->user();
                    $extra['user'] = '#' . $u->id . ' (' . ($u->name ?? 'User') . ')';
                }
            }
            return $record->with(extra: $extra);
        });

        return $logger;
    }
}
