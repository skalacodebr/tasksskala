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
        Schema::create('contatos_wp', function (Blueprint $table) {
            $table->id();
            $table->string('remote_jid');
            $table->string('push_name')->nullable();
            $table->text('profile_pic_url')->nullable();
            $table->string('instance_id');
            $table->string('instance_name');
            $table->boolean('is_group')->default(false);
            $table->timestamps();
            
            // Índices para melhor performance
            $table->index(['remote_jid', 'instance_name']);
            $table->index('instance_name');
            $table->index('is_group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contatos_wp');
    }
};
