<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->string('cedula', 20)->unique()->primary(); 
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('management', 160);
            $table->string('state', 70);
            $table->string('type_employee', 120);
            $table->string('position', 100);
            $table->string('phone', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
