<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('COUNTRIES', function (Blueprint $table) {
            $table->id('country_id');
            $table->string('country_name', 100)->unique();
            $table->string('country_code', 10)->unique();
            $table->string('region', 50)->nullable();
            $table->enum('is_active', ['Y', 'N'])->default('Y');
            // Using DB::raw for CURRENT_TIMESTAMP to ensure compatibility
            // and avoiding ON UPDATE for SQLite compatibility for now.
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('modified_date')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('COUNTRIES');
    }
};
