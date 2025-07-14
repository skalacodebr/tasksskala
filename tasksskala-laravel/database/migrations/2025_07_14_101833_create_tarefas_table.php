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
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->foreignId('colaborador_id')->constrained('colaboradores')->onDelete('cascade');
            $table->foreignId('projeto_id')->nullable()->constrained('projetos')->onDelete('cascade');
            $table->enum('tipo', ['manual', 'automatica_feedback', 'automatica_aprovacao'])->default('manual');
            $table->enum('prioridade', ['baixa', 'media', 'alta', 'urgente'])->default('media');
            $table->enum('status', ['pendente', 'em_andamento', 'concluida', 'cancelada'])->default('pendente');
            $table->datetime('data_vencimento')->nullable();
            $table->datetime('data_inicio')->nullable();
            $table->datetime('data_fim')->nullable();
            $table->text('observacoes')->nullable();
            $table->boolean('recorrente')->default(false);
            $table->enum('frequencia_recorrencia', ['diaria', 'semanal', 'mensal'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarefas');
    }
};
