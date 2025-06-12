<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('CONTACT_TYPES', function (Blueprint $table) {
            $table->id('contact_type_id');
            $table->string('contact_type_name', 50)->unique();
            $table->string('contact_type_code', 10)->unique();
            $table->string('description', 200)->nullable();
            $table->string('validation_pattern', 100)->nullable()->comment('نمط التحقق regex للهاتف والإيميل');
            $table->enum('is_active', ['Y', 'N'])->default('Y');
            $table->integer('display_order')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('CONTACT_TYPES');
    }
};
