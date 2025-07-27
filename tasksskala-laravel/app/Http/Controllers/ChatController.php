<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ChatSession;

class ChatController extends Controller
{
    private $apiKey = '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';
    
    public function index()
    {
        return view('agente-chat.index');
    }
    
    public function start(Request $request)
    {
        $validated = $request->validate([
            'workflow_path' => 'required|string'
        ]);
        
        try {
            // Carregar o workflow do arquivo JSON
            $workflowPath = storage_path('app/workflows/' . $validated['workflow_path']);
            
            if (!file_exists($workflowPath)) {
                return response()->json([
                    'error' => 'Workflow não encontrado'
                ], 404);
            }
            
            $workflow = json_decode(file_get_contents($workflowPath), true);
            
            if (!$workflow || !isset($workflow['steps'])) {
                return response()->json([
                    'error' => 'Workflow inválido'
                ], 400);
            }
            
            // Criar nova sessão de chat
            $session = ChatSession::create([
                'session_id' => uniqid('chat_'),
                'workflow' => $workflow,
                'current_step' => 0,
                'collected_data' => [],
                'conversation_history' => [],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            // Pegar primeiro passo
            $firstStep = $workflow['steps'][0];
            
            // Processar template do prompt
            $prompt = $this->processPromptTemplate($firstStep['prompt'], $session->collected_data);
            
            // Gerar resposta com GPT-4
            $response = $this->generateGPTResponse($prompt, $session->conversation_history);
            
            // Atualizar histórico da conversa
            $conversationHistory = $session->conversation_history;
            $conversationHistory[] = [
                'role' => 'assistant',
                'content' => $response,
                'step' => 0
            ];
            
            $session->update([
                'conversation_history' => $conversationHistory
            ]);
            
            return response()->json([
                'session_id' => $session->session_id,
                'response' => $response,
                'current_step' => 0,
                'total_steps' => count($workflow['steps'])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao iniciar chat: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erro ao iniciar chat',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function send(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string'
        ]);
        
        try {
            // Buscar sessão
            $session = ChatSession::where('session_id', $validated['session_id'])->first();
            
            if (!$session) {
                return response()->json([
                    'error' => 'Sessão não encontrada'
                ], 404);
            }
            
            $workflow = $session->workflow;
            $currentStep = $session->current_step;
            
            // Adicionar mensagem do usuário ao histórico
            $conversationHistory = $session->conversation_history;
            $conversationHistory[] = [
                'role' => 'user',
                'content' => $validated['message'],
                'step' => $currentStep
            ];
            
            // Processar a resposta do usuário e extrair dados se necessário
            $currentStepData = $workflow['steps'][$currentStep];
            if (isset($currentStepData['data_extraction'])) {
                $extractedData = $this->extractData(
                    $validated['message'],
                    $currentStepData['data_extraction'],
                    $conversationHistory
                );
                
                $collectedData = $session->collected_data;
                $collectedData = array_merge($collectedData, $extractedData);
                $session->collected_data = $collectedData;
            }
            
            // Verificar condições para próximo passo
            $nextStep = $this->determineNextStep($workflow, $currentStep, $session->collected_data);
            
            if ($nextStep === null) {
                // Workflow concluído
                $finalPrompt = $this->processPromptTemplate(
                    $workflow['completion']['prompt'] ?? 'Obrigado por sua participação!',
                    $session->collected_data
                );
                
                $response = $this->generateGPTResponse($finalPrompt, $conversationHistory);
                
                $conversationHistory[] = [
                    'role' => 'assistant',
                    'content' => $response,
                    'step' => 'completed'
                ];
                
                $session->update([
                    'conversation_history' => $conversationHistory,
                    'status' => 'completed'
                ]);
                
                return response()->json([
                    'session_id' => $session->session_id,
                    'response' => $response,
                    'completed' => true,
                    'collected_data' => $session->collected_data
                ]);
            }
            
            // Gerar resposta para o próximo passo
            $nextStepData = $workflow['steps'][$nextStep];
            $prompt = $this->processPromptTemplate($nextStepData['prompt'], $session->collected_data);
            $response = $this->generateGPTResponse($prompt, $conversationHistory);
            
            $conversationHistory[] = [
                'role' => 'assistant',
                'content' => $response,
                'step' => $nextStep
            ];
            
            // Atualizar sessão
            $session->update([
                'current_step' => $nextStep,
                'conversation_history' => $conversationHistory
            ]);
            
            return response()->json([
                'session_id' => $session->session_id,
                'response' => $response,
                'current_step' => $nextStep,
                'total_steps' => count($workflow['steps']),
                'completed' => false
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao processar mensagem: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erro ao processar mensagem',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    private function processPromptTemplate($template, $data)
    {
        // Substituir variáveis no template
        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }
    
    private function generateGPTResponse($prompt, $conversationHistory)
    {
        set_time_limit(120);
        
        $messages = [
            [
                'role' => 'system',
                'content' => 'Você é um assistente especializado em coleta de requisitos de software. 
                Seja claro, objetivo e amigável. Faça perguntas de forma natural e conversacional.
                Sempre seja educado e profissional.'
            ]
        ];
        
        // Adicionar histórico relevante (últimas 10 mensagens)
        $relevantHistory = array_slice($conversationHistory, -10);
        foreach ($relevantHistory as $msg) {
            $messages[] = [
                'role' => $msg['role'],
                'content' => $msg['content']
            ];
        }
        
        // Adicionar prompt atual
        $messages[] = [
            'role' => 'user',
            'content' => $prompt
        ];
        
        $response = Http::timeout(60)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4',
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 1000
            ]);
        
        if ($response->failed()) {
            throw new \Exception('Erro ao chamar API OpenAI: ' . $response->body());
        }
        
        return $response->json()['choices'][0]['message']['content'];
    }
    
    private function extractData($userMessage, $extractionRules, $conversationHistory)
    {
        $prompt = "Com base na seguinte conversa e mensagem do usuário, extraia as informações solicitadas em formato JSON.\n\n";
        $prompt .= "Conversa anterior:\n";
        
        // Adicionar contexto relevante
        $relevantHistory = array_slice($conversationHistory, -5);
        foreach ($relevantHistory as $msg) {
            $prompt .= $msg['role'] . ": " . $msg['content'] . "\n";
        }
        
        $prompt .= "\nMensagem atual do usuário: " . $userMessage . "\n\n";
        $prompt .= "Extraia as seguintes informações:\n";
        
        foreach ($extractionRules as $field => $description) {
            $prompt .= "- " . $field . ": " . $description . "\n";
        }
        
        $prompt .= "\nRetorne apenas o JSON com os campos extraídos. Se alguma informação não estiver disponível, use null.";
        
        $response = Http::timeout(60)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'Você é um assistente especializado em extração de dados. Retorne apenas JSON válido.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.3,
                'max_tokens' => 500
            ]);
        
        if ($response->failed()) {
            Log::error('Erro na extração de dados: ' . $response->body());
            return [];
        }
        
        try {
            $content = $response->json()['choices'][0]['message']['content'];
            // Limpar possíveis marcações de código
            $content = preg_replace('/```json\s*|\s*```/', '', $content);
            return json_decode($content, true) ?? [];
        } catch (\Exception $e) {
            Log::error('Erro ao decodificar JSON extraído: ' . $e->getMessage());
            return [];
        }
    }
    
    private function determineNextStep($workflow, $currentStep, $collectedData)
    {
        $currentStepData = $workflow['steps'][$currentStep];
        
        // Verificar se há condições
        if (isset($currentStepData['next_conditions'])) {
            foreach ($currentStepData['next_conditions'] as $condition) {
                if ($this->evaluateCondition($condition['condition'], $collectedData)) {
                    return $this->findStepByName($workflow, $condition['go_to']);
                }
            }
        }
        
        // Se não há condições ou nenhuma foi satisfeita, ir para o próximo passo sequencial
        $nextStep = $currentStep + 1;
        if ($nextStep < count($workflow['steps'])) {
            return $nextStep;
        }
        
        // Workflow concluído
        return null;
    }
    
    private function evaluateCondition($condition, $data)
    {
        // Implementação simplificada de avaliação de condições
        // Formato esperado: "field == value" ou "field != value"
        $parts = preg_split('/\s*(==|!=|>|<|>=|<=)\s*/', $condition, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        if (count($parts) !== 3) {
            return false;
        }
        
        $field = trim($parts[0]);
        $operator = $parts[1];
        $value = trim($parts[2], '"\'');
        
        if (!isset($data[$field])) {
            return false;
        }
        
        $fieldValue = $data[$field];
        
        switch ($operator) {
            case '==':
                return $fieldValue == $value;
            case '!=':
                return $fieldValue != $value;
            case '>':
                return $fieldValue > $value;
            case '<':
                return $fieldValue < $value;
            case '>=':
                return $fieldValue >= $value;
            case '<=':
                return $fieldValue <= $value;
            default:
                return false;
        }
    }
    
    private function findStepByName($workflow, $stepName)
    {
        foreach ($workflow['steps'] as $index => $step) {
            if (isset($step['name']) && $step['name'] === $stepName) {
                return $index;
            }
        }
        return null;
    }
}