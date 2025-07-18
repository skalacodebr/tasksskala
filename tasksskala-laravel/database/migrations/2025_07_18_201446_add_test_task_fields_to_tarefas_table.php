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
        Schema::table('tarefas', function (Blueprint $table) {
            $table->boolean('criar_tarefa_teste')->default(false)->after('frequencia_recorrencia');
            $table->unsignedBigInteger('testador_id')->nullable()->after('criar_tarefa_teste');
            $table->unsignedBigInteger('tarefa_origem_id')->nullable()->after('testador_id');
            $table->unsignedBigInteger('tarefa_teste_id')->nullable()->after('tarefa_origem_id');
            
            $table->foreign('testador_id')->references('id')->on('colaboradores')->onDelete('set null');
            $table->foreign('tarefa_origem_id')->references('id')->on('tarefas')->onDelete('cascade');
            $table->foreign('tarefa_teste_id')->references('id')->on('tarefas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarefas', function (Blueprint $table) {
            $table->dropForeign(['testador_id']);
            $table->dropForeign(['tarefa_origem_id']);
            $table->dropForeign(['tarefa_teste_id']);
            
            $table->dropColumn(['criar_tarefa_teste', 'testador_id', 'tarefa_origem_id', 'tarefa_teste_id']);
        });
    }
};
