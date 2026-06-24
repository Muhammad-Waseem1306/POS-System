<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class CleanupBackups extends Command
{
    protected $signature   = 'backup:cleanup {--days=30 : Retention period in days}';
    protected $description = 'Remove old backups based on retention policy';

    public function handle(BackupService $backupService): int
    {
        $days    = (int) $this->option('days');
        $deleted = $backupService->cleanup($days);
        $this->info("Removed {$deleted} old backup(s) older than {$days} days.");
        return self::SUCCESS;
    }
}
