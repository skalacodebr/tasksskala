<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ContatoWp;

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
}
