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
        Schema::create('employee_made_payments', function (Blueprint $table) {
            $table->id('id_employee_made_payment');
            $table->string('cedula_employee');
            $table->string('name_employee');
            $table->string('phone_employee')->nullable();
            $table->string('management');
            $table->unsignedBigInteger('id_order');
            $table->timestamps();

            // Clave foránea que relaciona con 'orders'
            $table->foreign('id_order')->references('number_order')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_made_payments');
    }
};
