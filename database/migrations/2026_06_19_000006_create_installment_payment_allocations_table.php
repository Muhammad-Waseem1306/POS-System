<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('installment_payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_transaction_id')->constrained('order_transactions')->cascadeOnDelete();
            $table->foreignId('installment_plan_id')->constrained('installment_plans')->cascadeOnDelete();
            $table->foreignId('installment_schedule_id')->constrained('installment_schedules')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->timestamp('allocated_at')->nullable();
            $table->timestamps();

            $table->index(['installment_plan_id', 'installment_schedule_id'], 'installment_allocation_plan_schedule_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_payment_allocations');
    }
};
