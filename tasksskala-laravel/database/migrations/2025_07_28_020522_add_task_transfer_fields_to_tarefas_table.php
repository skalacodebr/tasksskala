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
            $table->foreignId('transferido_de_id')->nullable()->constrained('colaboradores')->onDelete('set null');
            $table->foreignId('transferido_para_id')->nullable()->constrained('colaboradores')->onDelete('set null');
            $table->timestamp('data_transferencia')->nullable();
            $table->text('motivo_transferencia')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarefas', function (Blueprint $table) {
            $table->dropForeign(['transferido_de_id']);
            $table->dropForeign(['transferido_para_id']);
            $table->dropColumn(['transferido_de_id', 'transferido_para_id', 'data_transferencia', 'motivo_transferencia']);
        });
    }
};
