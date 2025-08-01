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
        Schema::create('messages_wp', function (Blueprint $table) {
            $table->id();
            $table->string('message_id'); // ID da mensagem do WhatsApp
            $table->string('remote_jid'); // Remetente/Destinatário
            $table->boolean('from_me')->default(false); // Se foi enviada por mim
            $table->string('push_name')->nullable(); // Nome do contato
            $table->string('status')->nullable(); // Status da mensagem
            $table->text('message_text')->nullable(); // Texto da mensagem (conversation)
            $table->string('message_type'); // Tipo: conversation, imageMessage, audioMessage, etc
            $table->string('media_url')->nullable(); // URL do arquivo de mídia (se houver)
            $table->string('media_type')->nullable(); // Tipo de mídia: image, audio, video, etc
            $table->bigInteger('message_timestamp'); // Timestamp da mensagem
            $table->string('instance_id'); // ID da instância
            $table->string('instance_name'); // Nome da instância
            $table->json('raw_data')->nullable(); // JSON completo da mensagem
            $table->timestamps();
            
            // Índices para melhor performance
            $table->index(['remote_jid', 'instance_name']);
            $table->index(['message_id', 'instance_name']);
            $table->index('instance_name');
            $table->index('from_me');
            $table->index('message_type');
            $table->index('message_timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages_wp');
    }
};
