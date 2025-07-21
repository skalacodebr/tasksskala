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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('projeto_id')->nullable()->constrained('projetos')->onDelete('set null');
            $table->enum('tipo', ['sugestao', 'reclamacao', 'elogio', 'duvida', 'outro']);
            $table->enum('prioridade', ['baixa', 'media', 'alta', 'urgente'])->default('media');
            $table->string('assunto');
            $table->text('mensagem');
            $table->text('resposta')->nullable();
            $table->timestamp('respondido_em')->nullable();
            $table->foreignId('respondido_por')->nullable()->constrained('colaboradores')->onDelete('set null');
            $table->enum('status', ['pendente', 'em_analise', 'respondido', 'resolvido', 'arquivado'])->default('pendente');
            $table->integer('avaliacao')->nullable(); // 1-5 estrelas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
