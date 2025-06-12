<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PATIENT_MEDICAL_HISTORY', function (Blueprint $table) {
            $table->id('history_id');
            $table->unsignedBigInteger('patient_id');
            $table->string('condition_category', 50)->nullable()->comment('فئة المرض');
            $table->string('disease_name', 200);
            $table->string('icd_code', 20)->nullable()->comment('كود ICD-10');
            $table->date('diagnosis_date')->nullable();
            $table->string('diagnosed_by', 100)->nullable()->comment('الطبيب المشخص');
            $table->text('treatment_received')->nullable();
            $table->enum('surgery_performed', ['Y', 'N'])->default('N');
            $table->date('surgery_date')->nullable();
            $table->text('surgery_details')->nullable();
            $table->enum('current_status', ['Active', 'Resolved', 'Chronic', 'In Remission', 'Stable'])->nullable();
            $table->enum('severity', ['Mild', 'Moderate', 'Severe'])->nullable();
            $table->enum('family_history', ['Y', 'N'])->default('N');
            $table->enum('genetic_component', ['Y', 'N'])->default('N');
            $table->string('recurrence_risk', 20)->nullable();
            $table->text('notes')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('modified_date')->default(DB::raw('CURRENT_TIMESTAMP')); // Fixed for SQLite

            $table->foreign('patient_id', 'fk_history_patient')
                  ->references('patient_id')->on('PATIENTS')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PATIENT_MEDICAL_HISTORY');
    }
};
