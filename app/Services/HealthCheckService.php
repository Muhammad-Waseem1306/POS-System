<?php

namespace App\Services;

use App\Models\BackupLog;
use App\Models\Product;
use App\Models\InstallmentSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HealthCheckService
{
    public function runAll(): array
    {
        return [
            'database'      => $this->checkDatabase(),
            'storage'       => $this->checkStorage(),
            'folders'       => $this->checkRequiredFolders(),
            'configuration' => $this->checkConfiguration(),
            'backup'        => $this->checkBackupHealth(),
            'queue'         => $this->checkQueue(),
        ];
    }

    public function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            $tables = DB::select('SHOW TABLES');
            return [
                'status'  => 'ok',
                'message' => 'Database connected. ' . count($tables) . ' tables found.',
            ];
        } catch (\Exception $e) {
            return [
                'status'   => 'critical',
                'message'  => 'Database connection failed: ' . $e->getMessage(),
                'recovery' => 'Check your .env DB_* credentials and ensure MySQL is running.',
            ];
        }
    }

    public function checkStorage(): array
    {
        try {
            $testFile = 'health_check_' . time() . '.tmp';
            Storage::disk('public')->put($testFile, 'test');
            Storage::disk('public')->delete($testFile);
            return ['status' => 'ok', 'message' => 'Storage is writable.'];
        } catch (\Exception $e) {
            return [
                'status'   => 'critical',
                'message'  => 'Storage not writable: ' . $e->getMessage(),
                'recovery' => 'Run: php artisan storage:link and check folder permissions on storage/',
            ];
        }
    }

    public function checkRequiredFolders(): array
    {
        $required = [
            storage_path('app/public'),
            storage_path('app/backups'),
            storage_path('logs'),
            public_path('storage'),
        ];

        $missing = [];
        foreach ($required as $folder) {
            if (!is_dir($folder)) {
                $missing[] = $folder;
            }
        }

        if (empty($missing)) {
            return ['status' => 'ok', 'message' => 'All required folders exist.'];
        }

        return [
            'status'   => 'warning',
            'message'  => 'Missing folders: ' . implode(', ', $missing),
            'recovery' => 'Run: php artisan storage:link and create missing directories.',
        ];
    }

    public function checkConfiguration(): array
    {
        $issues = [];

        if (config('app.key') === null || config('app.key') === '') {
            $issues[] = 'APP_KEY is not set.';
        }

        if (config('app.env') === 'production' && config('app.debug') === true) {
            $issues[] = 'APP_DEBUG should be false in production.';
        }

        if (config('mail.default') === 'log' && config('app.env') === 'production') {
            $issues[] = 'Mail driver is set to "log" — emails will not be sent.';
        }

        if (empty($issues)) {
            return ['status' => 'ok', 'message' => 'Configuration looks correct.'];
        }

        return [
            'status'  => 'warning',
            'message' => implode(' | ', $issues),
        ];
    }

    public function checkBackupHealth(): array
    {
        $lastBackup = BackupLog::where('status', 'success')->latest()->first();

        if (!$lastBackup) {
            return [
                'status'   => 'critical',
                'message'  => 'No backup has ever been created.',
                'recovery' => 'Go to Backup Management and run a manual backup immediately.',
            ];
        }

        $hours = now()->diffInHours($lastBackup->created_at);

        if ($hours > 25) {
            return [
                'status'  => 'warning',
                'message' => "Last backup was {$hours} hours ago ({$lastBackup->created_at->diffForHumans()}).",
            ];
        }

        return [
            'status'  => 'ok',
            'message' => "Last backup: {$lastBackup->created_at->diffForHumans()} ({$lastBackup->filename})",
        ];
    }

    public function checkQueue(): array
    {
        try {
            $failed = DB::table('failed_jobs')->count();
            if ($failed > 0) {
                return [
                    'status'   => 'warning',
                    'message'  => "{$failed} failed job(s) in queue.",
                    'recovery' => 'Run: php artisan queue:retry all',
                ];
            }
            return ['status' => 'ok', 'message' => 'No failed jobs.'];
        } catch (\Exception $e) {
            return ['status' => 'ok', 'message' => 'Queue table not checked.'];
        }
    }

    public function overallStatus(array $checks): string
    {
        foreach ($checks as $check) {
            if (($check['status'] ?? '') === 'critical') return 'critical';
        }
        foreach ($checks as $check) {
            if (($check['status'] ?? '') === 'warning') return 'warning';
        }
        return 'healthy';
    }
}
