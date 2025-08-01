<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppInstanceController extends Controller
{
    private $apiUrl = 'https://apiwp.skconnect.com.br';
    private $apiKey = '4fk6xm78dgyd6j32oiq43dgmbqtoryxr';

    public function index()
    {
        // Buscar instâncias existentes
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->get($this->apiUrl . '/instance/fetchInstances');

            if ($response->successful()) {
                $instances = $response->json();
            } else {
                $instances = [];
                Log::error('Erro ao buscar instâncias WhatsApp', ['response' => $response->body()]);
            }
        } catch (\Exception $e) {
            $instances = [];
            Log::error('Erro ao conectar com API WhatsApp', ['error' => $e->getMessage()]);
        }

        return view('admin.whatsapp-instances.index', compact('instances'));
    }

    public function create()
    {
        return view('admin.whatsapp-instances.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'instanceName' => 'required|string|max:255',
            'webhookUrl' => 'nullable|url'
        ]);

        $instanceName = $request->input('instanceName');
        $webhookUrl = $request->input('webhookUrl', 'https://webhook.site/f0913db5-0dbe-4805-b827-044ff7fa00d2');

        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->apiUrl . '/instance/create', [
                'instanceName' => $instanceName,
                'integration' => 'WHATSAPP-BAILEYS',
                'webhook' => [
                    'url' => $webhookUrl,
                    'byEvents' => true,
                    'base64' => true,
                    'events' => [
                        'MESSAGES_UPSERT',
                        'QRCODE_UPDATED',
                        'MESSAGES_UPSERT',
                        'CONTACTS_UPSERT',
                        'GROUPS_UPSERT'
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return redirect()->route('admin.whatsapp-instances.show', ['instanceName' => $instanceName])
                    ->with('success', 'Instância criada com sucesso! Aguarde o QR Code para conectar.');
            } else {
                $error = $response->json();
                if ($response->status() == 404 || (isset($error['message']) && str_contains($error['message'], 'already exists'))) {
                    return back()->with('error', 'Já existe uma instância com este nome.');
                }
                return back()->with('error', 'Erro ao criar instância: ' . ($error['message'] ?? 'Erro desconhecido'));
            }
        } catch (\Exception $e) {
            Log::error('Erro ao criar instância WhatsApp', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erro ao conectar com a API: ' . $e->getMessage());
        }
    }

    public function show($instanceName)
    {
        try {
            // Buscar QR Code
            $qrResponse = Http::withHeaders([
                'apikey' => $this->apiKey
            ])->get($this->apiUrl . '/instance/connect/' . $instanceName);

            $qrCode = null;
            $status = 'disconnected';

            if ($qrResponse->successful()) {
                $data = $qrResponse->json();
                if (isset($data['qrcode'])) {
                    $qrCode = $data['qrcode'];
                    $status = 'waiting_qr';
                }
            }

            // Verificar status da conexão
            $statusResponse = Http::withHeaders([
                'apikey' => $this->apiKey
            ])->get($this->apiUrl . '/instance/connectionState/' . $instanceName);

            if ($statusResponse->successful()) {
                $statusData = $statusResponse->json();
                if (isset($statusData['state']) && $statusData['state'] == 'open') {
                    $status = 'connected';
                }
            }

            return view('admin.whatsapp-instances.show', compact('instanceName', 'qrCode', 'status'));
        } catch (\Exception $e) {
            Log::error('Erro ao buscar detalhes da instância', ['error' => $e->getMessage()]);
            return redirect()->route('admin.whatsapp-instances.index')
                ->with('error', 'Erro ao buscar detalhes da instância');
        }
    }

    public function destroy($instanceName)
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey
            ])->delete($this->apiUrl . '/instance/delete/' . $instanceName);

            if ($response->successful()) {
                return redirect()->route('admin.whatsapp-instances.index')
                    ->with('success', 'Instância removida com sucesso!');
            } else {
                return back()->with('error', 'Erro ao remover instância');
            }
        } catch (\Exception $e) {
            Log::error('Erro ao deletar instância WhatsApp', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erro ao conectar com a API');
        }
    }

    public function disconnect($instanceName)
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey
            ])->delete($this->apiUrl . '/instance/logout/' . $instanceName);

            if ($response->successful()) {
                return back()->with('success', 'Instância desconectada com sucesso!');
            } else {
                return back()->with('error', 'Erro ao desconectar instância');
            }
        } catch (\Exception $e) {
            Log::error('Erro ao desconectar instância WhatsApp', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erro ao conectar com a API');
        }
    }
}