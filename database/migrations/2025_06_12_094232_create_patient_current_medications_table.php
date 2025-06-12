<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PATIENT_CURRENT_MEDICATIONS', function (Blueprint $table) {
            $table->id('medication_id');
            $table->unsignedBigInteger('patient_id');
            $table->string('medication_name', 200);
            $table->string('generic_name', 200)->nullable();
            $table->string('medication_code', 50)->nullable()->comment('كود الدواء');
            $table->string('dosage', 100)->nullable();
            $table->string('dosage_form', 50)->nullable()->comment('شكل الجرعة');
            $table->string('route', 50)->nullable()->comment('طريقة الإعطاء');
            $table->string('frequency', 100)->nullable();
            $table->tinyInteger('frequency_times_per_day')->nullable();
            $table->smallInteger('duration_days')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('prescribing_doctor', 100)->nullable();
            $table->date('prescribing_date')->nullable();
            $table->string('prescription_number', 50)->nullable();
            $table->string('indication', 500)->nullable()->comment('سبب الوصف');
            $table->text('side_effects_experienced')->nullable();
            $table->tinyInteger('effectiveness_rating')->nullable();
            $table->enum('compliance_level', ['Excellent', 'Good', 'Fair', 'Poor'])->nullable();
            $table->enum('is_active', ['Y', 'N'])->default('Y');
            $table->string('discontinuation_reason', 500)->nullable();
            $table->date('discontinuation_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('modified_date')->default(DB::raw('CURRENT_TIMESTAMP')); // Fixed for SQLite

            $table->foreign('patient_id', 'fk_medications_patient')
                  ->references('patient_id')->on('PATIENTS')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PATIENT_CURRENT_MEDICATIONS');
    }
};
