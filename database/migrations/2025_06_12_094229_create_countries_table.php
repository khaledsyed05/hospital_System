<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('COUNTRIES', function (Blueprint $table) {
            $table->id('country_id');
            $table->string('country_name', 100)->unique();
            $table->string('country_code', 10)->unique();
            $table->string('region', 50)->nullable();
            $table->enum('is_active', ['Y', 'N'])->default('Y');
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            // Removed ON UPDATE CURRENT_TIMESTAMP for SQLite compatibility
            $table->timestamp('modified_date')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('COUNTRIES');
    }
};
