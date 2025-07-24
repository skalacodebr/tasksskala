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
        Schema::create('ticket_mensagens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->text('mensagem');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->foreignId('colaborador_id')->nullable()->constrained('colaboradores')->onDelete('set null');
            $table->boolean('is_internal')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_mensagens');
    }
};