<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PATIENT_MEDICAL_INFO', function (Blueprint $table) {
            $table->id('medical_info_id');
            $table->unsignedBigInteger('patient_id')->unique();
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->enum('smoking_status', ['Never', 'Current', 'Former'])->nullable();
            $table->enum('alcohol_status', ['Never', 'Occasional', 'Regular', 'Former'])->nullable();
            $table->string('exercise_frequency', 20)->nullable();
            $table->string('dietary_restrictions', 500)->nullable();
            $table->text('general_notes')->nullable();
            // Removed ON UPDATE CURRENT_TIMESTAMP for SQLite compatibility
            $table->timestamp('last_updated')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('updated_by', 50)->nullable();

            $table->foreign('patient_id', 'fk_medical_info_patient')
                  ->references('patient_id')->on('PATIENTS')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PATIENT_MEDICAL_INFO');
    }
};
