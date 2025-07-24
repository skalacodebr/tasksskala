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
        Schema::table('contas_pagar', function (Blueprint $table) {
            $table->dropColumn('fornecedor');
            $table->foreignId('fornecedor_id')->nullable()->after('categoria_id')->constrained('fornecedores');
        });
        
        Schema::table('contas_receber', function (Blueprint $table) {
            $table->foreignId('projeto_id')->nullable()->after('cliente_id')->constrained('projetos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contas_pagar', function (Blueprint $table) {
            $table->dropForeign(['fornecedor_id']);
            $table->dropColumn('fornecedor_id');
            $table->string('fornecedor')->nullable();
        });
        
        Schema::table('contas_receber', function (Blueprint $table) {
            $table->dropForeign(['projeto_id']);
            $table->dropColumn('projeto_id');
        });
    }
};
