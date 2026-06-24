<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('license', function (Blueprint $table) {
            $table->id();
            $table->string('store_name');
            $table->string('store_address')->nullable();
            $table->string('store_phone')->nullable();
            $table->string('store_email')->nullable();
            $table->string('license_key')->nullable();
            $table->date('license_expires_at')->nullable();
            $table->string('license_type')->default('standard'); // standard, professional, enterprise
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('license');
    }
};
