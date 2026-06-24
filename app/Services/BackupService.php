<?php

namespace App\Services;

use App\Models\BackupLog;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BackupService
{
    private string $backupDisk = 'local';
    private string $backupPath = 'backups';

    public function run(string $type = 'manual', ?int $createdBy = null): array
    {
        $filename   = $this->generateFilename($type);
        $log        = BackupLog::create([
            'filename'   => $filename,
            'type'       => $type,
            'status'     => 'in_progress',
            'created_by' => $createdBy ?? auth()->id(),
        ]);

        try {
            $sql  = $this->dumpDatabase();
            $path = "{$this->backupPath}/{$filename}";

            Storage::disk($this->backupDisk)->put($path, $sql);

            $size = Storage::disk($this->backupDisk)->size($path);

            $log->update([
                'status' => 'success',
                'size'   => $size,
                'path'   => $path,
            ]);

            return ['success' => true, 'log' => $log->fresh(), 'filename' => $filename];
        } catch (\Throwable $e) {
            $log->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            SystemNotification::createNotification(
                'backup_failed',
                'Backup Failed',
                "The {$type} backup failed: " . $e->getMessage(),
                'danger'
            );

            return ['success' => false, 'error' => $e->getMessage(), 'log' => $log->fresh()];
        }
    }

    public function restore(int $logId): array
    {
        $log = BackupLog::findOrFail($logId);

        if ($log->status !== 'success') {
            return ['success' => false, 'error' => 'Cannot restore from a failed or in-progress backup.'];
        }

        if (!Storage::disk($this->backupDisk)->exists($log->path)) {
            return ['success' => false, 'error' => 'Backup file not found on disk.'];
        }

        try {
            $sql = Storage::disk($this->backupDisk)->get($log->path);
            $this->importSql($sql);

            AuditService::log('restore', 'backup', $logId, "Database restored from backup: {$log->filename}");

            return ['success' => true, 'message' => 'Database restored successfully.'];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function delete(int $logId): array
    {
        $log = BackupLog::findOrFail($logId);

        if ($log->path && Storage::disk($this->backupDisk)->exists($log->path)) {
            Storage::disk($this->backupDisk)->delete($log->path);
        }

        $log->delete();
        return ['success' => true];
    }

    public function download(int $logId): ?string
    {
        $log = BackupLog::find($logId);
        if (!$log || !$log->path) return null;
        if (!Storage::disk($this->backupDisk)->exists($log->path)) return null;
        return Storage::disk($this->backupDisk)->path($log->path);
    }

    public function getHealthStatus(): array
    {
        $lastSuccess = BackupLog::where('status', 'success')->latest()->first();
        $lastBackup  = BackupLog::latest()->first();
        $totalSize   = BackupLog::where('status', 'success')->sum('size');
        $failedToday = BackupLog::where('status', 'failed')
            ->whereDate('created_at', today())
            ->count();

        $hoursSinceLastBackup = $lastSuccess
            ? now()->diffInHours($lastSuccess->created_at)
            : null;

        $status = 'healthy';
        $alerts = [];

        if (!$lastSuccess) {
            $status   = 'critical';
            $alerts[] = 'No successful backup has ever been created.';
        } elseif ($hoursSinceLastBackup > 25) {
            $status   = 'warning';
            $alerts[] = "Last backup was {$hoursSinceLastBackup} hours ago.";
        }

        if ($failedToday > 0) {
            $status   = $status === 'healthy' ? 'warning' : $status;
            $alerts[] = "{$failedToday} backup(s) failed today.";
        }

        return compact('lastSuccess', 'lastBackup', 'totalSize', 'failedToday', 'hoursSinceLastBackup', 'status', 'alerts');
    }

    public function cleanup(int $retentionDays = 30, string $type = null): int
    {
        $query = BackupLog::where('status', 'success')
            ->where('created_at', '<', now()->subDays($retentionDays));

        if ($type) {
            $query->where('type', $type);
        }

        $logs  = $query->get();
        $count = 0;

        foreach ($logs as $log) {
            if ($log->path && Storage::disk($this->backupDisk)->exists($log->path)) {
                Storage::disk($this->backupDisk)->delete($log->path);
            }
            $log->delete();
            $count++;
        }

        return $count;
    }

    private function generateFilename(string $type): string
    {
        $db        = config('database.connections.mysql.database');
        $timestamp = now()->format('Y-m-d_H-i-s');
        return "backup_{$db}_{$type}_{$timestamp}.sql";
    }

    private function dumpDatabase(): string
    {
        $db       = config('database.connections.mysql.database');
        $host     = config('database.connections.mysql.host');
        $port     = config('database.connections.mysql.port', 3306);
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        // Build SQL dump using PDO (portable, no mysqldump dependency)
        $pdo    = DB::getPdo();
        $output = [];

        $output[] = "-- QPOS Database Backup";
        $output[] = "-- Generated: " . now()->toDateTimeString();
        $output[] = "-- Database: {$db}";
        $output[] = "-- -----------------------------------------------";
        $output[] = "";
        $output[] = "SET FOREIGN_KEY_CHECKS=0;";
        $output[] = "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';";
        $output[] = "";

        $tables = DB::select('SHOW TABLES');
        $tableKey = "Tables_in_{$db}";

        foreach ($tables as $tableRow) {
            $table = $tableRow->$tableKey;

            // Table structure
            $createTable = DB::select("SHOW CREATE TABLE `{$table}`");
            $createSql   = $createTable[0]->{'Create Table'};
            $output[]    = "DROP TABLE IF EXISTS `{$table}`;";
            $output[]    = $createSql . ";";
            $output[]    = "";

            // Table data
            $rows = DB::table($table)->get();
            if ($rows->isEmpty()) {
                $output[] = "-- No data in `{$table}`";
                $output[] = "";
                continue;
            }

            $columns = array_keys((array) $rows->first());
            $cols    = '`' . implode('`, `', $columns) . '`';

            foreach ($rows->chunk(100) as $chunk) {
                $valueGroups = [];
                foreach ($chunk as $row) {
                    $values = array_map(function ($val) use ($pdo) {
                        if ($val === null) return 'NULL';
                        return $pdo->quote($val);
                    }, (array) $row);
                    $valueGroups[] = '(' . implode(', ', $values) . ')';
                }
                $output[] = "INSERT INTO `{$table}` ({$cols}) VALUES";
                $output[] = implode(",\n", $valueGroups) . ";";
            }

            $output[] = "";
        }

        $output[] = "SET FOREIGN_KEY_CHECKS=1;";

        return implode("\n", $output);
    }

    private function importSql(string $sql): void
    {
        $statements = array_filter(
            array_map('trim', explode(";\n", $sql)),
            fn($s) => !empty($s) && !str_starts_with($s, '--')
        );

        DB::unprepared("SET FOREIGN_KEY_CHECKS=0;");
        foreach ($statements as $stmt) {
            if (!empty(trim($stmt))) {
                DB::unprepared($stmt . ';');
            }
        }
        DB::unprepared("SET FOREIGN_KEY_CHECKS=1;");
    }
}
