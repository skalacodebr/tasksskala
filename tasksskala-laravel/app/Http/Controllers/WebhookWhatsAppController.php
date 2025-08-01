<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\ContatoWp;
use App\Models\MessageWp;

class WebhookWhatsAppController extends Controller
{
    public function handle(Request $request)
    {
        try {
            // Log do webhook recebido para debug
            Log::info('Webhook WhatsApp recebido', $request->all());
            
            $event = $request->input('event');
            $instance = $request->input('instance');
            $data = $request->input('data', []);
            
            // Verificar se é o evento de contatos
            if ($event === 'contacts.upsert' && !empty($data) && !empty($instance)) {
                $this->processContactsUpsert($instance, $data);
            }
            
            // Verificar se é o evento de mensagens
            if ($event === 'messages.upsert' && !empty($data) && !empty($instance)) {
                $this->processMessagesUpsert($instance, $data);
            }
            
            return response()->json(['status' => 'success'], 200);
            
        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook WhatsApp', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    private function processContactsUpsert($instanceName, $contacts)
    {
        foreach ($contacts as $contact) {
            try {
                $remoteJid = $contact['remoteJid'] ?? null;
                $pushName = $contact['pushName'] ?? null;
                $profilePicUrl = $contact['profilePicUrl'] ?? null;
                $instanceId = $contact['instanceId'] ?? null;
                
                if (!$remoteJid) {
                    continue; // Pular se não tiver remoteJid
                }
                
                // Determinar se é grupo baseado no remoteJid
                $isGroup = str_contains($remoteJid, '@g.us');
                
                // Verificar se já existe o contato
                $existingContact = ContatoWp::where('remote_jid', $remoteJid)
                    ->where('instance_name', $instanceName)
                    ->first();
                
                if ($existingContact) {
                    // Atualizar contato existente
                    $existingContact->update([
                        'push_name' => $pushName,
                        'profile_pic_url' => $profilePicUrl,
                        'instance_id' => $instanceId,
                        'is_group' => $isGroup
                    ]);
                    
                    Log::info('Contato WhatsApp atualizado', [
                        'remote_jid' => $remoteJid,
                        'instance' => $instanceName,
                        'is_group' => $isGroup
                    ]);
                } else {
                    // Criar novo contato
                    ContatoWp::create([
                        'remote_jid' => $remoteJid,
                        'push_name' => $pushName,
                        'profile_pic_url' => $profilePicUrl,
                        'instance_id' => $instanceId,
                        'instance_name' => $instanceName,
                        'is_group' => $isGroup
                    ]);
                    
                    Log::info('Novo contato WhatsApp criado', [
                        'remote_jid' => $remoteJid,
                        'instance' => $instanceName,
                        'is_group' => $isGroup
                    ]);
                }
                
            } catch (\Exception $e) {
                Log::error('Erro ao processar contato individual', [
                    'contact' => $contact,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
    
    private function processMessagesUpsert($instanceName, $messageData)
    {
        try {
            // Extrair dados da mensagem
            $key = $messageData['key'] ?? [];
            $message = $messageData['message'] ?? [];
            
            $messageId = $key['id'] ?? null;
            $remoteJid = $key['remoteJid'] ?? null;
            $fromMe = $key['fromMe'] ?? false;
            $pushName = $messageData['pushName'] ?? null;
            $status = $messageData['status'] ?? null;
            $messageType = $messageData['messageType'] ?? null;
            $messageTimestamp = $messageData['messageTimestamp'] ?? null;
            $instanceId = $messageData['instanceId'] ?? null;
            
            if (!$messageId || !$remoteJid) {
                Log::warning('Mensagem sem ID ou remoteJid', $messageData);
                return;
            }
            
            // Verificar se o contato existe, se não existir, criar
            $this->ensureContactExists($instanceName, $remoteJid, $pushName, $instanceId);
            
            // Verificar se a mensagem já existe
            $existingMessage = MessageWp::where('message_id', $messageId)
                ->where('instance_name', $instanceName)
                ->first();
                
            if ($existingMessage) {
                Log::info('Mensagem já existe, pulando', [
                    'message_id' => $messageId,
                    'instance' => $instanceName
                ]);
                return;
            }
            
            $messageText = null;
            $mediaUrl = null;
            $mediaType = null;
            
            // Processar diferentes tipos de mensagem
            switch ($messageType) {
                case 'conversation':
                    $messageText = $message['conversation'] ?? null;
                    break;
                    
                case 'imageMessage':
                    $messageText = '[Imagem]';
                    $mediaType = 'image';
                    if (isset($message['imageMessage']['base64'])) {
                        $mediaUrl = $this->saveBase64Media($message['imageMessage']['base64'], 'image', $messageId);
                    }
                    break;
                    
                case 'audioMessage':
                    $messageText = '[Áudio]';
                    $mediaType = 'audio';
                    if (isset($message['audioMessage']['base64'])) {
                        $mediaUrl = $this->saveBase64Media($message['audioMessage']['base64'], 'audio', $messageId);
                    }
                    break;
                    
                default:
                    $messageText = '[Mensagem não suportada: ' . $messageType . ']';
            }
            
            // Criar registro da mensagem
            MessageWp::create([
                'message_id' => $messageId,
                'remote_jid' => $remoteJid,
                'from_me' => $fromMe,
                'push_name' => $pushName,
                'status' => $status,
                'message_text' => $messageText,
                'message_type' => $messageType,
                'media_url' => $mediaUrl,
                'media_type' => $mediaType,
                'message_timestamp' => $messageTimestamp,
                'instance_id' => $instanceId,
                'instance_name' => $instanceName,
                'raw_data' => $messageData
            ]);
            
            Log::info('Nova mensagem WhatsApp salva', [
                'message_id' => $messageId,
                'remote_jid' => $remoteJid,
                'from_me' => $fromMe,
                'type' => $messageType,
                'instance' => $instanceName
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao processar mensagem individual', [
                'message_data' => $messageData,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function saveBase64Media($base64Data, $mediaType, $messageId)
    {
        try {
            // Remover prefixo do base64 se existir
            if (strpos($base64Data, 'data:') === 0) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
            }
            
            // Decodificar base64
            $decodedData = base64_decode($base64Data);
            if ($decodedData === false) {
                throw new \Exception('Falha ao decodificar base64');
            }
            
            // Determinar extensão do arquivo
            $extension = $this->getMediaExtension($mediaType, $decodedData);
            
            // Criar nome do arquivo
            $fileName = 'whatsapp_' . $mediaType . '_' . $messageId . '.' . $extension;
            $filePath = 'whatsapp-media/' . $fileName;
            
            // Criar diretório se não existir
            if (!Storage::exists('whatsapp-media')) {
                Storage::makeDirectory('whatsapp-media');
            }
            
            // Salvar arquivo
            Storage::put($filePath, $decodedData);
            
            // Retornar URL pública do arquivo
            return Storage::url($filePath);
            
        } catch (\Exception $e) {
            Log::error('Erro ao salvar mídia base64', [
                'media_type' => $mediaType,
                'message_id' => $messageId,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }
    
    private function getMediaExtension($mediaType, $data)
    {
        // Detectar tipo de arquivo pelos primeiros bytes
        $header = substr($data, 0, 20);
        
        if ($mediaType === 'image') {
            if (strpos($header, "\xFF\xD8\xFF") === 0) return 'jpg';
            if (strpos($header, "\x89PNG") === 0) return 'png';
            if (strpos($header, "GIF87a") === 0 || strpos($header, "GIF89a") === 0) return 'gif';
            if (strpos($header, "WEBP") !== false) return 'webp';
            return 'jpg'; // fallback
        }
        
        if ($mediaType === 'audio') {
            if (strpos($header, "OggS") === 0) return 'ogg';
            if (strpos($header, "\xFF\xFB") === 0 || strpos($header, "\xFF\xF3") === 0 || strpos($header, "\xFF\xF2") === 0) return 'mp3';
            if (strpos($header, "RIFF") === 0 && strpos($header, "WAVE") !== false) return 'wav';
            return 'ogg'; // fallback para WhatsApp (geralmente opus em ogg)
        }
        
        return 'bin'; // fallback genérico
    }
    
    private function ensureContactExists($instanceName, $remoteJid, $pushName, $instanceId)
    {
        try {
            // Verificar se o contato já existe
            $existingContact = ContatoWp::where('remote_jid', $remoteJid)
                ->where('instance_name', $instanceName)
                ->first();
            
            if (!$existingContact) {
                // Determinar se é grupo baseado no remoteJid
                $isGroup = str_contains($remoteJid, '@g.us');
                
                // Criar novo contato
                ContatoWp::create([
                    'remote_jid' => $remoteJid,
                    'push_name' => $pushName,
                    'profile_pic_url' => null,
                    'instance_id' => $instanceId,
                    'instance_name' => $instanceName,
                    'is_group' => $isGroup
                ]);
                
                Log::info('Novo contato criado automaticamente via mensagem', [
                    'remote_jid' => $remoteJid,
                    'push_name' => $pushName,
                    'instance' => $instanceName,
                    'is_group' => $isGroup
                ]);
            } elseif ($existingContact->push_name !== $pushName && !empty($pushName)) {
                // Atualizar nome do contato se mudou
                $existingContact->update([
                    'push_name' => $pushName,
                    'instance_id' => $instanceId
                ]);
                
                Log::info('Nome do contato atualizado', [
                    'remote_jid' => $remoteJid,
                    'old_name' => $existingContact->push_name,
                    'new_name' => $pushName,
                    'instance' => $instanceName
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar/atualizar contato automaticamente', [
                'remote_jid' => $remoteJid,
                'push_name' => $pushName,
                'instance' => $instanceName,
                'error' => $e->getMessage()
            ]);
        }
    }
}
