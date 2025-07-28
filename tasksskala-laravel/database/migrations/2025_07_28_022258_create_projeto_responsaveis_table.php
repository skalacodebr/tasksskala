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
        Schema::create('projeto_responsaveis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projeto_id')->constrained('projetos')->onDelete('cascade');
            $table->foreignId('colaborador_id')->constrained('colaboradores')->onDelete('cascade');
            $table->timestamps();
            
            // Índice único para evitar duplicatas
            $table->unique(['projeto_id', 'colaborador_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projeto_responsaveis');
    }
};
