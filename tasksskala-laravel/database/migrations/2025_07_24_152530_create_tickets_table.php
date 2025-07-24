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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao');
            $table->enum('setor', ['comercial', 'financeiro', 'desenvolvimento']);
            $table->enum('prioridade', ['baixa', 'media', 'alta'])->default('media');
            $table->enum('status', ['aberto', 'em_andamento', 'respondido', 'fechado'])->default('aberto');
            $table->foreignId('projeto_id')->nullable()->constrained('projetos')->onDelete('set null');
            $table->foreignId('atribuido_para')->nullable()->constrained('colaboradores')->onDelete('set null');
            $table->timestamp('respondido_em')->nullable();
            $table->timestamp('fechado_em')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};