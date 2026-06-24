<?php

namespace App\Console\Commands;

use App\Models\InstallmentSchedule;
use App\Models\SystemNotification;
use Illuminate\Console\Command;

class CheckOverdueInstallments extends Command
{
    protected $signature   = 'installments:check-overdue';
    protected $description = 'Check for overdue installment payments and create notifications';

    public function handle(): int
    {
        $overdue = InstallmentSchedule::where('status', 'pending')
            ->where('due_date', '<', today())
            ->with(['installmentPlan.customer'])
            ->get();

        $count = 0;
        foreach ($overdue as $schedule) {
            InstallmentSchedule::where('id', $schedule->id)->update(['status' => 'overdue']);

            $existing = SystemNotification::where('type', 'overdue_installment')
                ->where('reference_type', InstallmentSchedule::class)
                ->where('reference_id', $schedule->id)
                ->where('is_read', false)
                ->exists();

            if (!$existing) {
                $customer = $schedule->installmentPlan->customer->name ?? 'Unknown';
                SystemNotification::createNotification(
                    'overdue_installment',
                    'Overdue Installment',
                    "Installment #{$schedule->installment_number} for customer \"{$customer}\" was due on {$schedule->due_date}. Amount: {$schedule->amount}",
                    'danger',
                    route('backend.admin.installments.show', $schedule->installment_plan_id),
                    InstallmentSchedule::class,
                    $schedule->id
                );
                $count++;
            }
        }

        $this->info("Checked overdue installments. Created {$count} new notification(s).");
        return self::SUCCESS;
    }
}
