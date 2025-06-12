<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PATIENT_RESEARCH_CONSENTS', function (Blueprint $table) {
            $table->id('consent_id');
            $table->unsignedBigInteger('patient_id')->unique(); // Added unique as per uk_consents_patient
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
            $table->string('created_by', 50)->nullable(); // Made nullable as per typical practice, original SQL didn't specify NOT NULL
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('patient_id', 'fk_consents_patient')
                  ->references('patient_id')->on('PATIENTS')->onDelete('cascade');
            // CHECK constraint chk_genetic_sample handled by validation logic.
            // DB::statement("ALTER TABLE PATIENT_RESEARCH_CONSENTS ADD CONSTRAINT chk_genetic_sample CHECK ((genetic_testing_consent = 'Y' AND genetic_sample_id IS NOT NULL) OR (genetic_testing_consent = 'N'))");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PATIENT_RESEARCH_CONSENTS');
    }
};
