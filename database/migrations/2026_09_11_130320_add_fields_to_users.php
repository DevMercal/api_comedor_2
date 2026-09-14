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
        Schema::table('users', function (Blueprint $table) {
            $table->string('cedula', 20)->after('id');
            $table->bigInteger('id_time_token')->unsigned()->after('cedula');
            $table->bigInteger('id_expiry_month')->unsigned()->after('id_time_token');

            $table->foreign('cedula')->references('cedula')->on('employees')->onDelete('cascade');
            $table->foreign('id_time_token')->references('id_time_token')->on('time_tokens')->onDelete('cascade');
            $table->foreign('id_expiry_month')->references('id_expiry_month')->on('expiry_months')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
