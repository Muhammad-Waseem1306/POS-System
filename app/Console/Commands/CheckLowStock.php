<?php

namespace App\Console\Commands;

use App\Services\StockService;
use Illuminate\Console\Command;

class CheckLowStock extends Command
{
    protected $signature   = 'stock:check-low';
    protected $description = 'Check all products for low stock and create notifications';

    public function handle(): int
    {
        $count = StockService::checkAllLowStock();
        $this->info("Low stock check complete. Created {$count} new alert(s).");
        return self::SUCCESS;
    }
}
