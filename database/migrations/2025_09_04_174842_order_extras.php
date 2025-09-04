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
        Schema::create('order_extras', function (Blueprint $table) {
            $table->unsignedBigInteger('id_order');
            $table->unsignedBigInteger('id_extra');
            $table->timestamps();
            // Definir las claves foráneas que hacen referencia a las tablas 'orders' y 'extras'
            $table->foreign('id_order')->references('number_order')->on('orders')->onDelete('cascade');
            $table->foreign('id_extra')->references('id_extra')->on('extras')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
