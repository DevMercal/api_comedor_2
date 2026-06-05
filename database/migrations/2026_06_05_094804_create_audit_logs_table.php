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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('entity');      // Ejemplo: 'App\Models\User'
            $table->unsignedBigInteger('entity_id'); // ID del usuario afectado
            $table->string('action');      // created, updated, deleted
            $table->json('old_values')->nullable(); // Estado antes del cambio
            $table->json('new_values')->nullable(); // Estado después del cambio
            $table->foreignId('user_id')->nullable()->constrained('users'); // Quién hizo la acción (JWT Auth)
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
