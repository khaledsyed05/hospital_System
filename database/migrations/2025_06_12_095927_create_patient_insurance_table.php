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
        Schema::create('PATIENT_INSURANCE', function (Blueprint $table) {
            $table->id('insurance_id');
            $table->unsignedBigInteger('patient_id');
            $table->integer('insurance_company_id')->nullable()->comment('سيرتبط بجدول شركات التأمين لاحقاً');
            $table->enum('insurance_type', ['Government', 'Private', 'Self-Pay', 'Military'])->nullable();
            $table->string('policy_number', 50)->nullable();
            $table->string('group_number', 50)->nullable();
            // CHECK (coverage_percentage BETWEEN 0 AND 100) -> App level
            $table->tinyInteger('coverage_percentage')->nullable();
            $table->decimal('deductible_amount', 10, 2)->nullable();
            $table->decimal('copay_amount', 8, 2)->nullable();
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable(); // CHECK (expiry_date IS NULL OR expiry_date >= effective_date) -> App level
            $table->enum('payment_method', ['Cash', 'Card', 'Insurance', 'Bank Transfer'])->nullable();
            $table->enum('is_primary', ['Y', 'N'])->default('Y');
            $table->enum('is_active', ['Y', 'N'])->default('Y');
            $table->string('notes', 500)->nullable();
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('modified_date')->default(DB::raw('CURRENT_TIMESTAMP')); // SQLite compatible

            $table->foreign('patient_id', 'fk_insurance_patient')
                  ->references('patient_id')->on('PATIENTS')
                  ->onDelete('cascade')->onUpdate('cascade');

            // UNIQUE (policy_number, insurance_company_id)
            $table->unique(['policy_number', 'insurance_company_id'], 'uk_insurance_policy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PATIENT_INSURANCE');
    }
};
