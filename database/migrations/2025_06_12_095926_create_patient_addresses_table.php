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
        Schema::create('PATIENT_ADDRESSES', function (Blueprint $table) {
            $table->id('address_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('address_type_id');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('street', 500)->nullable();
            $table->string('building_number', 20)->nullable();
            $table->string('apartment_number', 20)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('landmarks', 200)->nullable()->comment('معالم مميزة');
            $table->enum('is_primary', ['Y', 'N'])->default('N');
            $table->enum('is_active', ['Y', 'N'])->default('Y');
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('modified_date')->default(DB::raw('CURRENT_TIMESTAMP')); // SQLite compatible

            $table->foreign('patient_id', 'fk_addresses_patient')
                  ->references('patient_id')->on('PATIENTS')
                  ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('address_type_id', 'fk_addresses_type')
                  ->references('address_type_id')->on('ADDRESS_TYPES')
                  ->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('country_id', 'fk_addresses_country')
                  ->references('country_id')->on('COUNTRIES')
                  ->onDelete('set null')->onUpdate('cascade'); // Assuming SET NULL on delete is desired if country is removed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PATIENT_ADDRESSES');
    }
};
