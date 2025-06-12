<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ADDRESS_TYPES', function (Blueprint $table) {
            $table->id('address_type_id');
            $table->string('address_type_name', 50)->unique();
            $table->string('address_type_code', 10)->unique();
            $table->string('description', 200)->nullable();
            $table->enum('is_active', ['Y', 'N'])->default('Y');
            $table->integer('display_order')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ADDRESS_TYPES');
    }
};
