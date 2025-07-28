<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrar os responsáveis atuais para a tabela pivot
        DB::table('projetos')
            ->whereNotNull('colaborador_responsavel_id')
            ->orderBy('id')
            ->chunk(100, function ($projetos) {
                foreach ($projetos as $projeto) {
                    // Inserir o responsável principal na tabela pivot
                    DB::table('projeto_responsaveis')->insert([
                        'projeto_id' => $projeto->id,
                        'colaborador_id' => $projeto->colaborador_responsavel_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Limpar a tabela pivot
        DB::table('projeto_responsaveis')->truncate();
    }
};
