<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Colaborador;

class WhatsAppInstanceController extends Controller
{
    private $apiUrl = 'https://apiwp.skconnect.com.br';
    private $apiKey = '4fk6xm78dgyd6j32oiq43dgmbqtoryxr';

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $colaborador = Colaborador::with('setor')->find(session('colaborador_id'));
            
            if (!$colaborador || $colaborador->setor->nome !== 'Administrativo') {
                return redirect()->route('dashboard')->with('error', 'Acesso negado. Esta funcionalidade é exclusiva para o setor Administrativo.');
            }
            
            return $next($request);
        });
    }

    public function index()
    {
        // Buscar instâncias existentes
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->get($this->apiUrl . '/instance/fetchInstances');

            if ($response->successful()) {
                $data = $response->json();
                // A resposta pode vir em diferentes formatos, vamos normalizar
                if (isset($data['instances'])) {
                    $instances = $data['instances'];
                } elseif (isset($data['data'])) {
                    $instances = $data['data'];
                } elseif (is_array($data) && !empty($data) && !isset($data[0])) {
                    // Se for um objeto único, transformar em array
                    $instances = [$data];
                } else {
                    $instances = $data;
                }
                
                Log::info('Instâncias WhatsApp encontradas', ['count' => count($instances), 'data' => $instances]);
            } else {
                $instances = [];
                Log::error('Erro ao buscar instâncias WhatsApp', ['response' => $response->body()]);
            }
        } catch (\Exception $e) {
            $instances = [];
            Log::error('Erro ao conectar com API WhatsApp', ['error' => $e->getMessage()]);
        }

        return view('whatsapp-instances.index', compact('instances'));
    }

    public function create()
    {
        return view('whatsapp-instances.create');
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
                return redirect()->route('whatsapp-instances.show', ['instanceName' => $instanceName])
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
            // Primeiro verificar status da conexão
            $statusResponse = Http::withHeaders([
                'apikey' => $this->apiKey
            ])->get($this->apiUrl . '/instance/connectionState/' . $instanceName);

            $status = 'disconnected';
            $qrCode = null;
            $base64 = null;

            if ($statusResponse->successful()) {
                $statusData = $statusResponse->json();
                Log::info('Status da instância', $statusData);
                
                // Verificar o estado da instância
                $state = $statusData['instance']['state'] ?? ($statusData['state'] ?? 'closed');
                
                if ($state == 'open' || $state == 'connected') {
                    $status = 'connected';
                } elseif ($state == 'connecting' || $state == 'closed' || $state == 'disconnected') {
                    // Se está conectando ou desconectado, buscar QR Code
                    $qrResponse = Http::withHeaders([
                        'apikey' => $this->apiKey
                    ])->get($this->apiUrl . '/instance/connect/' . $instanceName);

                    if ($qrResponse->successful()) {
                        $qrData = $qrResponse->json();
                        Log::info('Resposta QR Code', $qrData);
                        
                        // O QR code pode vir em base64 ou como código
                        if (isset($qrData['base64'])) {
                            $base64 = $qrData['base64'];
                            $status = 'waiting_qr';
                        } elseif (isset($qrData['code'])) {
                            $qrCode = $qrData['code'];
                            $status = 'waiting_qr';
                        }
                    }
                }
            } else {
                // Se a instância não existe ou erro na API
                if ($statusResponse->status() == 404) {
                    return redirect()->route('whatsapp-instances.index')
                        ->with('error', 'Instância não encontrada');
                }
            }

            return view('whatsapp-instances.show', compact('instanceName', 'qrCode', 'status', 'base64'));
        } catch (\Exception $e) {
            Log::error('Erro ao buscar detalhes da instância', ['error' => $e->getMessage()]);
            return redirect()->route('whatsapp-instances.index')
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
                return redirect()->route('whatsapp-instances.index')
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