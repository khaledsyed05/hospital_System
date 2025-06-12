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
        Schema::create('PATIENT_MEDICAL_INFO', function (Blueprint $table) {
            $table->id('medical_info_id');
            // Original SQL implies patient_id is both FK and PK for this table (uk_medical_info_patient)
            // Thus, it should be unique.
            $table->unsignedBigInteger('patient_id')->unique();
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->decimal('weight', 5, 2)->nullable(); // CHECK (weight > 0 AND weight <= 999) -> App level
            $table->decimal('height', 5, 2)->nullable(); // CHECK (height > 0 AND height <= 999) -> App level
            // bmi DECIMAL(5,2) GENERATED ALWAYS AS (CASE WHEN height > 0 THEN ROUND(weight / POWER(height/100, 2), 2) ELSE NULL END)
            // Generated columns are not well supported across all DBs by Laravel Schema Builder.
            // This should be an accessor in the Eloquent Model (getBmiAttribute).
            $table->enum('smoking_status', ['Never', 'Current', 'Former'])->nullable();
            $table->enum('alcohol_status', ['Never', 'Occasional', 'Regular', 'Former'])->nullable();
            $table->string('exercise_frequency', 20)->nullable();
            $table->string('dietary_restrictions', 500)->nullable();
            $table->text('general_notes')->nullable();
            $table->timestamp('last_updated')->default(DB::raw('CURRENT_TIMESTAMP')); // SQLite compatible
            $table->string('updated_by', 50)->nullable();

            $table->foreign('patient_id', 'fk_medical_info_patient')
                  ->references('patient_id')->on('PATIENTS')
                  ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PATIENT_MEDICAL_INFO');
    }
};
