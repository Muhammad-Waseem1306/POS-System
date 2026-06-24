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
        Schema::table('installment_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('installment_plans', 'guarantor_id')) {
                $table->foreignId('guarantor_id')->nullable()->constrained('customer_guarantors')->cascadeOnDelete()->after('customer_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('installment_plans', function (Blueprint $table) {
            if (Schema::hasColumn('installment_plans', 'guarantor_id')) {
                $table->dropConstrainedForeignId('guarantor_id');
            }
        });
    }
};
