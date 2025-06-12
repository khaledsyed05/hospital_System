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
        Schema::create('PATIENTS', function (Blueprint $table) {
            $table->id('patient_id');
            $table->string('patient_number', 20)->unique()->comment('رقم المريض المقروء');
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('full_name_ar', 300)->nullable()->comment('الاسم الكامل بالعربية');
            $table->string('full_name_en', 300)->nullable()->comment('الاسم الكامل بالإنجليزية');
            $table->string('national_id', 30)->unique()->nullable(); // Ensure one of national_id or passport_number is NOT NULL (application level or trigger)
            $table->string('passport_number', 30)->unique()->nullable(); // Ensure one of national_id or passport_number is NOT NULL (application level or trigger)
            $table->date('date_of_birth');
            $table->integer('age_years')->nullable()->comment('العمر بالسنوات');
            $table->enum('gender', ['M', 'F']);
            $table->unsignedBigInteger('nationality_id')->nullable();
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed'])->nullable();
            $table->timestamp('registration_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('status', ['Active', 'Inactive', 'Deceased'])->default('Active');
            $table->date('deceased_date')->nullable(); // Ensure deceased_date logic with status (application level or trigger)
            $table->string('photo_path', 500)->nullable()->comment('مسار الصورة الشخصية');
            $table->text('notes')->nullable()->comment('ملاحظات عامة');
            $table->string('created_by', 50);
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('modified_by', 50)->nullable();
            $table->timestamp('modified_date')->default(DB::raw('CURRENT_TIMESTAMP')); // SQLite compatible

            $table->foreign('nationality_id', 'fk_patients_nationality')
                  ->references('country_id')->on('COUNTRIES')
                  ->onDelete('restrict')->onUpdate('cascade'); // Assuming restrict on delete is desired for nationality

            // Note on CHECK constraints from original SQL:
            // 1. chk_national_or_passport (national_id IS NOT NULL OR passport_number IS NOT NULL)
            // 2. chk_deceased_date ((status = 'Deceased' AND deceased_date IS NOT NULL) OR (status != 'Deceased' AND deceased_date IS NULL))
            // These are complex rules. Laravel's schema builder doesn't directly support CHECK constraints declaratively
            // in a way that's compatible with all database engines (especially SQLite).
            // These should be enforced at the application level (e.g., using Form Requests or model validation)
            // or via database triggers if using MySQL/PostgreSQL and you need DB-level enforcement.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PATIENTS');
    }
};
