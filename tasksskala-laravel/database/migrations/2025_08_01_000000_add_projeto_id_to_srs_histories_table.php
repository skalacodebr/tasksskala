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
        Schema::table('srs_histories', function (Blueprint $table) {
            $table->foreignId('projeto_id')->nullable()->after('user_agent')->constrained('projetos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('srs_histories', function (Blueprint $table) {
            $table->dropForeign(['projeto_id']);
            $table->dropColumn('projeto_id');
        });
    }
};