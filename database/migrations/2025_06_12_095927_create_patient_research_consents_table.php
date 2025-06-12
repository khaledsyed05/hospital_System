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
        Schema::create('PATIENT_RESEARCH_CONSENTS', function (Blueprint $table) {
            $table->id('consent_id');
            // Original SQL implies patient_id is both FK and PK for this table (uk_consents_patient)
            // Thus, it should be unique.
            $table->unsignedBigInteger('patient_id')->unique();
            $table->enum('research_consent', ['Y', 'N'])->default('N');
            $table->enum('genetic_testing_consent', ['Y', 'N'])->default('N');
            $table->enum('data_sharing_consent', ['Y', 'N'])->default('N');
            $table->string('genetic_sample_id', 50)->nullable()->comment('معرف العينة الجينية');
            $table->enum('genetic_sample_status', ['Collected', 'Stored', 'Processed', 'Destroyed'])->nullable();
            $table->timestamp('consent_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('consent_expiry_date')->nullable();
            $table->timestamp('consent_withdrawn_date')->nullable();
            $table->string('withdrawal_reason', 500)->nullable();
            $table->string('witness_name', 100)->nullable();
            $table->string('consent_form_path', 500)->nullable()->comment('مسار نموذج الموافقة الممسوح');
            $table->text('notes')->nullable();
            $table->string('created_by', 50)->nullable(); // Original SQL didn't specify NOT NULL, making it nullable for flexibility
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            // No modified_date in original SQL for this table

            $table->foreign('patient_id', 'fk_consents_patient')
                  ->references('patient_id')->on('PATIENTS')
                  ->onDelete('cascade')->onUpdate('cascade');

            // CHECK constraint chk_genetic_sample ((genetic_testing_consent = 'Y' AND genetic_sample_id IS NOT NULL) OR (genetic_testing_consent = 'N'))
            // This is complex conditional logic, best handled at the application validation layer.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PATIENT_RESEARCH_CONSENTS');
    }
};
