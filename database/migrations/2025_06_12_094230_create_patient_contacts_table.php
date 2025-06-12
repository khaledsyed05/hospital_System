<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PATIENT_CONTACTS', function (Blueprint $table) {
            $table->id('contact_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('contact_type_id');
            $table->string('contact_value', 100);
            $table->string('contact_person_name', 100)->nullable()->comment('اسم الشخص للطوارئ');
            $table->string('relationship', 50)->nullable()->comment('العلاقة للطوارئ');
            $table->enum('is_primary', ['Y', 'N'])->default('N');
            $table->enum('is_verified', ['Y', 'N'])->default('N');
            $table->timestamp('verification_date')->nullable();
            $table->enum('is_active', ['Y', 'N'])->default('Y');
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            // Removed ON UPDATE CURRENT_TIMESTAMP for SQLite compatibility
            $table->timestamp('modified_date')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('patient_id', 'fk_contacts_patient')
                  ->references('patient_id')->on('PATIENTS')->onDelete('cascade');
            $table->foreign('contact_type_id', 'fk_contacts_type')
                  ->references('contact_type_id')->on('CONTACT_TYPES');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PATIENT_CONTACTS');
    }
};
