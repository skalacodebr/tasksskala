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
        Schema::create('rankings_premios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colaborador_id')->constrained('colaboradores')->onDelete('cascade');
            $table->enum('tipo_periodo', ['mensal', 'anual']);
            $table->date('periodo_inicio');
            $table->date('periodo_fim');
            $table->integer('posicao');
            $table->decimal('pontuacao', 10, 2);
            $table->integer('tarefas_concluidas')->default(0);
            $table->decimal('media_por_dia', 8, 2)->default(0);
            $table->integer('projetos_trabalhados')->default(0);
            $table->decimal('tempo_medio_horas', 8, 2)->default(0);
            $table->string('premio_titulo')->nullable();
            $table->text('premio_descricao')->nullable();
            $table->boolean('premio_entregue')->default(false);
            $table->date('data_entrega_premio')->nullable();
            $table->timestamps();
            
            $table->index(['tipo_periodo', 'periodo_inicio', 'periodo_fim']);
            $table->index(['colaborador_id', 'tipo_periodo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rankings_premios');
    }
};
