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
        Schema::create('srs_histories', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable();
            $table->string('version', 20)->default('v2'); // v1 for teste_agente, v2 for teste_agente2
            $table->json('answers'); // Store all form answers
            $table->longText('srs_document'); // The generated SRS document
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('session_id');
            $table->index('version');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('srs_histories');
    }
};
