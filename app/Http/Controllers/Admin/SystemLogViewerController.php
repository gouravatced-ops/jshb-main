<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class SystemLogViewerController extends Controller
{
    /**
     * Check super admin authorization.
     */
    private function authorizeSuperAdmin(): User
    {
        if (!Auth::check()) {
            abort(401, 'Please log in to access this page.');
        }

        $user = Auth::user();
        $isSuperAdmin = ($user->roleRelation?->slug === 'super-admin' 
            || $user->role_id == 9 
            || $user->roleRelation?->slug === 'admin' 
            || $user->role_id == 8 
            || $user->user_type === 'administration');

        if (!$isSuperAdmin) {
            abort(403, 'Unauthorized access. System logs are reserved for Super Administrators.');
        }

        return $user;
    }

    /**
     * Display the standalone System Logs Console.
     */
    public function index(Request $request)
    {
        $this->authorizeSuperAdmin();

        $dates = $this->getAvailableDates();
        $today = date('Y-m-d');

        $selectedDate = $request->query('date', $today);
        if ($selectedDate !== 'root' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
            $selectedDate = $today;
        }

        $files = $this->getLogFilesForDate($selectedDate);
        $selectedFile = $request->query('file');

        if (!$selectedFile || !collect($files)->contains('name', $selectedFile)) {
            $defaultFile = collect($files)->firstWhere('name', 'laravel.log');
            $selectedFile = $defaultFile ? 'laravel.log' : ($files[0]['name'] ?? null);
        }

        $logData = $selectedFile ? $this->readLogFile($selectedDate, $selectedFile) : null;

        return view('admin.logs.system_logs', compact('dates', 'selectedDate', 'files', 'selectedFile', 'logData'));
    }

    /**
     * AJAX endpoint to fetch log content dynamically.
     */
    public function getContent(Request $request)
    {
        $this->authorizeSuperAdmin();

        $selectedDate = $request->query('date', date('Y-m-d'));
        if ($selectedDate !== 'root' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
            $selectedDate = date('Y-m-d');
        }

        $selectedFile = $request->query('file');
        if (!$selectedFile || !preg_match('/^[a-zA-Z0-9_\-\.]+\.log$/', $selectedFile)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid log file requested.'], 400);
        }

        $logData = $this->readLogFile($selectedDate, $selectedFile);
        if (!$logData) {
            return response()->json(['status' => 'error', 'message' => 'Log file not found.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $logData,
        ]);
    }

    /**
     * Download the selected log file.
     */
    public function download(Request $request)
    {
        $this->authorizeSuperAdmin();

        $selectedDate = $request->query('date', date('Y-m-d'));
        $selectedFile = $request->query('file');

        if (!$selectedFile || !preg_match('/^[a-zA-Z0-9_\-\.]+\.log$/', $selectedFile)) {
            abort(400, 'Invalid log file requested.');
        }

        $path = $selectedDate === 'root'
            ? storage_path('logs/' . $selectedFile)
            : storage_path('logs/' . $selectedDate . '/' . $selectedFile);

        if (!File::exists($path)) {
            abort(404, 'Log file not found.');
        }

        return response()->download($path, "{$selectedDate}_{$selectedFile}");
    }

    /**
     * Clear / truncate a log file.
     */
    public function clear(Request $request)
    {
        $this->authorizeSuperAdmin();

        $selectedDate = $request->input('date', date('Y-m-d'));
        $selectedFile = $request->input('file');

        if (!$selectedFile || !preg_match('/^[a-zA-Z0-9_\-\.]+\.log$/', $selectedFile)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid log file requested.'], 400);
        }

        $path = $selectedDate === 'root'
            ? storage_path('logs/' . $selectedFile)
            : storage_path('logs/' . $selectedDate . '/' . $selectedFile);

        if (!File::exists($path)) {
            return response()->json(['status' => 'error', 'message' => 'Log file not found.'], 404);
        }

        File::put($path, '');

        return response()->json([
            'status' => 'success',
            'message' => "Log file '{$selectedFile}' has been cleared successfully.",
        ]);
    }

    /**
     * Scan storage/logs for available date folders and root logs.
     */
    private function getAvailableDates(): array
    {
        $logsPath = storage_path('logs');
        $dates = [];

        if (File::isDirectory($logsPath)) {
            $directories = File::directories($logsPath);
            foreach ($directories as $dir) {
                $base = basename($dir);
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $base)) {
                    $dates[] = $base;
                }
            }
        }

        $today = date('Y-m-d');
        if (!in_array($today, $dates, true)) {
            $dates[] = $today;
        }

        rsort($dates);

        // Also check if root storage/logs has any .log files
        $rootLogs = File::glob($logsPath . '/*.log');
        if (!empty($rootLogs)) {
            $dates[] = 'root';
        }

        return $dates;
    }

    /**
     * Get all .log files in the selected date directory.
     */
    private function getLogFilesForDate(string $date): array
    {
        $path = $date === 'root'
            ? storage_path('logs')
            : storage_path('logs/' . $date);

        if (!File::isDirectory($path)) {
            return [];
        }

        $files = File::files($path);
        $result = [];

        foreach ($files as $file) {
            if ($file->getExtension() !== 'log') {
                continue;
            }

            $name = $file->getFilename();
            $bytes = $file->getSize();
            $category = $this->determineCategory($name);

            $result[] = [
                'name' => $name,
                'category' => $category,
                'size_human' => $this->formatBytes($bytes),
                'size_bytes' => $bytes,
                'modified_at' => Carbon::createFromTimestamp($file->getMTime())->format('d M Y, h:i A'),
            ];
        }

        // Sort: laravel.log first, then alphabetically
        usort($result, function ($a, $b) {
            if ($a['name'] === 'laravel.log') return -1;
            if ($b['name'] === 'laravel.log') return 1;
            return strcmp($a['name'], $b['name']);
        });

        return $result;
    }

    /**
     * Read and parse entries from the log file.
     */
    private function readLogFile(string $date, string $fileName): ?array
    {
        $path = $date === 'root'
            ? storage_path('logs/' . $fileName)
            : storage_path('logs/' . $date . '/' . $fileName);

        if (!File::exists($path)) {
            return null;
        }

        $bytes = File::size($path);
        $modifiedAt = Carbon::createFromTimestamp(File::lastModified($path))->format('d M Y, h:i:s A');

        // Read last 800 lines to prevent memory issues with massive log files
        $rawLines = $this->tailFile($path, 800);
        $rawText = implode("", $rawLines);

        $parsedEntries = $this->parseLogEntries($rawText);

        return [
            'file_name' => $fileName,
            'date' => $date,
            'size_human' => $this->formatBytes($bytes),
            'size_bytes' => $bytes,
            'modified_at' => $modifiedAt,
            'total_entries' => count($parsedEntries),
            'entries' => array_reverse($parsedEntries), // latest entries first
            'raw_text' => $rawText,
        ];
    }

    /**
     * Read last N lines from a file efficiently.
     */
    private function tailFile(string $filepath, int $lines = 800): array
    {
        $handle = @fopen($filepath, "r");
        if (!$handle) {
            return [];
        }

        $lineList = [];
        $buffer = 4096;
        fseek($handle, 0, SEEK_END);
        $pos = ftell($handle);
        $text = "";

        while ($pos > 0 && count($lineList) < $lines) {
            $seek = min($pos, $buffer);
            $pos -= $seek;
            fseek($handle, $pos);
            $chunk = fread($handle, $seek);
            $text = $chunk . $text;
            $lineList = explode("\n", $text);
        }

        fclose($handle);

        if (count($lineList) > $lines) {
            $lineList = array_slice($lineList, -$lines);
        }

        return array_map(fn($line) => $line . "\n", $lineList);
    }

    /**
     * Parse log lines into structured entries.
     */
    private function parseLogEntries(string $content): array
    {
        $pattern = '/^\[(?<date>\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:[+-]\d{2}:?\d{2}|Z)?)\]\s+(?:(?<env>[a-zA-Z0-9_\-]+)\.)?(?<level>EMERGENCY|ALERT|CRITICAL|ERROR|WARNING|NOTICE|INFO|DEBUG|SUCCESS|FAILED|START):\s*(?<message>.*?)(?=(?:^\[\d{4}-\d{2}-\d{2})|\z)/msi';

        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        $entries = [];
        foreach ($matches as $match) {
            $level = strtoupper(trim($match['level'] ?? 'INFO'));
            $date = trim($match['date'] ?? '');
            $message = trim($match['message'] ?? '');

            $entries[] = [
                'timestamp' => $date,
                'level' => $level,
                'message' => $message,
                'level_class' => $this->getLevelBadgeClass($level),
            ];
        }

        // If no regex match found (non-standard logs like plain text), create plain entries
        if (empty($entries) && trim($content) !== '') {
            $lines = array_filter(explode("\n", $content));
            foreach (array_slice($lines, -150) as $line) {
                $entries[] = [
                    'timestamp' => '—',
                    'level' => 'LOG',
                    'message' => trim($line),
                    'level_class' => 'bg-secondary',
                ];
            }
        }

        return $entries;
    }

    /**
     * Map log level to CSS badge class.
     */
    private function getLevelBadgeClass(string $level): string
    {
        return match ($level) {
            'EMERGENCY', 'ALERT', 'CRITICAL', 'ERROR', 'FAILED' => 'badge-error',
            'WARNING' => 'badge-warning',
            'NOTICE', 'INFO', 'SUCCESS', 'START' => 'badge-info',
            'DEBUG' => 'badge-debug',
            default => 'badge-secondary',
        };
    }

    /**
     * Categorize log file names into clear tags.
     */
    private function determineCategory(string $filename): string
    {
        if (str_contains($filename, 'mail')) return 'Email';
        if (str_contains($filename, 'otp')) return 'OTP';
        if (str_contains($filename, 'queue') || str_contains($filename, 'job')) return 'Queue';
        if (str_contains($filename, 'notification')) return 'Notification';
        if (str_contains($filename, 'sms')) return 'SMS';
        if (str_contains($filename, 'whatsapp')) return 'WhatsApp';
        if (str_contains($filename, 'application')) return 'Application';
        if (str_contains($filename, 'user') || str_contains($filename, 'credential')) return 'Security';
        if ($filename === 'laravel.log') return 'Framework';
        return 'System';
    }

    /**
     * Format bytes to human readable string.
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
