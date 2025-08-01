<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    private string $apiKey;
    private string $apiUrl = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = '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';
    }

    public function generateTestTaskDescription(string $originalTaskTitle, string $originalTaskDescription = null): string
    {
        try {
            $prompt = "Você é um assistente que cria descrições de tarefas de teste. ";
            $prompt .= "Com base na tarefa original abaixo, crie uma descrição clara e objetiva para uma tarefa de teste.\n\n";
            $prompt .= "Título da tarefa original: {$originalTaskTitle}\n";
            
            if ($originalTaskDescription) {
                $prompt .= "Descrição da tarefa original: {$originalTaskDescription}\n";
            }
            
            $prompt .= "\nCrie uma descrição de teste que inclua:\n";
            $prompt .= "1. O que deve ser testado\n";
            $prompt .= "2. Principais cenários de teste\n";
            $prompt .= "3. Critérios de aceitação\n";
            $prompt .= "\nMantenha a descrição concisa e profissional.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Você é um assistente especializado em criar tarefas de teste de software.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'];
            }

            return $this->getFallbackDescription($originalTaskTitle);
            
        } catch (\Exception $e) {
            return $this->getFallbackDescription($originalTaskTitle);
        }
    }

    private function getFallbackDescription(string $originalTaskTitle): string
    {
        return "Testar a funcionalidade: {$originalTaskTitle}\n\n" .
               "Verificar:\n" .
               "1. Funcionamento correto da implementação\n" .
               "2. Casos de uso principais\n" .
               "3. Possíveis cenários de erro\n" .
               "4. Compatibilidade com o sistema existente";
    }
}