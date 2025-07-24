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
            $table->text('notas')->nullable()->after('observacoes');
            $table->timestamp('data_pausa')->nullable()->after('data_fim');
            $table->integer('tempo_pausado')->default(0)->after('data_pausa')->comment('Tempo total pausado em segundos');
            $table->boolean('pausada')->default(false)->after('tempo_pausado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarefas', function (Blueprint $table) {
            $table->dropColumn(['notas', 'data_pausa', 'tempo_pausado', 'pausada']);
        });
    }
};