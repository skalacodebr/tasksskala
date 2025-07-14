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
        Schema::table('projetos', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('projetos', function (Blueprint $table) {
            $table->enum('status', ['em_andamento', 'aprovacao_app', 'concluido', 'pausado', 'cancelado'])->default('em_andamento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projetos', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('projetos', function (Blueprint $table) {
            $table->enum('status', ['em_andamento', 'concluido', 'pausado', 'cancelado'])->default('em_andamento');
        });
    }
};
