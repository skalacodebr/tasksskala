<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait WhatsAppNotification
{
    protected function enviarNotificacaoWhatsApp($phone, $message)
    {
        if (empty($phone)) {
            return false;
        }
        
        try {
            // Preparar o payload no formato correto da API
            $payload = [
                'number' => $phone,
                'text' => $message
            ];
            
            // Enviar usando cURL
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, config('whatsapp.api_url'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'apikey: ' . config('whatsapp.api_key'),
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            
            curl_close($ch);
            
            if ($error) {
                Log::error('Erro cURL ao enviar notificação WhatsApp: ' . $error);
                return false;
            } elseif ($httpCode >= 200 && $httpCode < 300) {
                Log::info('Notificação WhatsApp enviada com sucesso para: ' . $phone);
                return true;
            } else {
                Log::error('Erro HTTP ao enviar notificação WhatsApp: HTTP ' . $httpCode . ' - ' . $response);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exceção ao enviar notificação WhatsApp: ' . $e->getMessage());
            return false;
        }
    }
}