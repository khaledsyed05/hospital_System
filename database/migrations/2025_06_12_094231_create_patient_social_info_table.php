<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PATIENT_SOCIAL_INFO', function (Blueprint $table) {
            $table->id('social_id');
            $table->unsignedBigInteger('patient_id')->unique();
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed', 'Separated'])->nullable();
            $table->string('occupation', 100)->nullable();
            $table->string('employer', 200)->nullable();
            $table->string('work_phone', 20)->nullable();
            $table->enum('education_level', ['None', 'Primary', 'Secondary', 'Diploma', 'Bachelor', 'Master', 'PhD'])->nullable();
            $table->tinyInteger('family_size')->nullable();
            $table->string('preferred_language', 50)->default('Arabic');
            $table->string('religion', 50)->nullable();
            $table->text('cultural_considerations')->nullable();
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('modified_date')->default(DB::raw('CURRENT_TIMESTAMP')); // Fixed for SQLite

            $table->foreign('patient_id', 'fk_social_info_patient')
                  ->references('patient_id')->on('PATIENTS')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PATIENT_SOCIAL_INFO');
    }
};
