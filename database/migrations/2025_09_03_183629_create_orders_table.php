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
        Schema::create('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('number_order')->primary();
            $table->string('special_event', 70);
            $table->string('authorized', 70);
            $table->string('authorized_person', 70);
            $table->bigInteger('id_payment_method')->unsigned();
            $table->integer('reference')->unsigned()->unique();
            $table->string('total_amount');
            $table->bigInteger('id_employee')->unsigned();
            $table->bigInteger('id_order_status')->unsigned();
            $table->bigInteger('id_orders_consumption')->unsigned();
            $table->date('date_order')->nullable();
            $table->timestamps();

            $table->foreign('id_payment_method')->references('id_payment_method')->on('payment_methods')->onDelete('cascade');
            $table->foreign('id_employee')->references('id_employee')->on('employees')->onDelete('cascade');
            $table->foreign('id_order_status')->references('id_order_status')->on('order_statuses')->onDelete('cascade');
            $table->foreign('id_orders_consumption')->references('id_orders_consumption')->on('order_consumptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
