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
        Schema::create('PATIENT_ALLERGIES', function (Blueprint $table) {
            $table->id('allergy_id');
            $table->unsignedBigInteger('patient_id');
            $table->enum('allergy_type', ['Drug', 'Food', 'Environmental', 'Contact', 'Other']);
            $table->string('allergen_name', 200);
            $table->string('allergen_code', 50)->nullable()->comment('كود معياري للحساسية');
            $table->enum('severity', ['Mild', 'Moderate', 'Severe', 'Life-threatening'])->nullable();
            $table->string('reaction_type', 50)->nullable();
            $table->text('reaction_description')->nullable();
            $table->date('date_discovered')->nullable();
            $table->string('discovered_method', 100)->nullable()->comment('كيف تم اكتشافها');
            $table->enum('confirmed_by_test', ['Y', 'N'])->default('N');
            $table->date('test_date')->nullable();
            $table->string('test_results', 500)->nullable();
            $table->string('treatment_given', 500)->nullable();
            $table->enum('is_active', ['Y', 'N'])->default('Y');
            $table->text('notes')->nullable();
            $table->string('created_by', 50)->nullable(); // Original SQL didn't specify NOT NULL
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('modified_date')->default(DB::raw('CURRENT_TIMESTAMP')); // SQLite compatible

            $table->foreign('patient_id', 'fk_allergies_patient')
                  ->references('patient_id')->on('PATIENTS')
                  ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PATIENT_ALLERGIES');
    }
};
