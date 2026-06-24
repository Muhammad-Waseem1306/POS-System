<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class RunBackup extends Command
{
    protected $signature   = 'backup:run {--type=manual : Backup type (manual, hourly, daily, weekly, monthly)}';
    protected $description = 'Run a database backup';

    public function handle(BackupService $backupService): int
    {
        $type = $this->option('type');
        $this->info("Starting {$type} backup...");

        $result = $backupService->run($type, null);

        if ($result['success']) {
            $log = $result['log'];
            $this->info("Backup completed: {$log->filename} ({$log->formatted_size})");
            return self::SUCCESS;
        }

        $this->error("Backup failed: {$result['error']}");
        return self::FAILURE;
    }
}
