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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('cnic', 30)->nullable()->unique()->after('phone');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('sale_type', 20)->default('cash')->after('customer_id')->index();
        });

        Schema::table('order_transactions', function (Blueprint $table) {
            $table->timestamp('paid_at')->nullable()->after('paid_by');
            $table->string('reference_number')->nullable()->after('transaction_id');
            $table->text('notes')->nullable()->after('reference_number');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('model')->nullable()->after('name');
            $table->unsignedSmallInteger('warranty_period_months')->nullable()->after('expire_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique('customers_cnic_unique');
            $table->dropColumn('cnic');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_sale_type_index');
            $table->dropColumn('sale_type');
        });

        Schema::table('order_transactions', function (Blueprint $table) {
            $table->dropColumn(['paid_at', 'reference_number', 'notes']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['model', 'warranty_period_months']);
        });
    }
};
