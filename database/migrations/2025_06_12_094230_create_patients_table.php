<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
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
            $table->string('national_id', 30)->unique()->nullable();
            $table->string('passport_number', 30)->unique()->nullable();
            $table->date('date_of_birth');
            $table->integer('age_years')->nullable()->comment('العمر بالسنوات');
            $table->enum('gender', ['M', 'F']);
            $table->unsignedBigInteger('nationality_id')->nullable();
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed'])->nullable();
            $table->timestamp('registration_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('status', ['Active', 'Inactive', 'Deceased'])->default('Active');
            $table->date('deceased_date')->nullable();
            $table->string('photo_path', 500)->nullable()->comment('مسار الصورة الشخصية');
            $table->text('notes')->nullable()->comment('ملاحظات عامة');
            $table->string('created_by', 50);
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('modified_by', 50)->nullable();
            // Removed ON UPDATE CURRENT_TIMESTAMP for SQLite compatibility
            $table->timestamp('modified_date')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('nationality_id', 'fk_patients_nationality')
                  ->references('country_id')->on('COUNTRIES');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PATIENTS');
    }
};
