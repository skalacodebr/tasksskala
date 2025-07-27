<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\SrsHistory;

class AgenteSRS2Controller extends Controller
{
    public function getQuestions()
    {
        return $this->questions;
    }
    
    private $questions = [
        'proposito_escopo' => [
            'title' => '1. Propósito e Escopo do Projeto',
            'description' => 'Base: ISO/IEC/IEEE 29148:2018 - Seções 9.6.2 (Purpose), 9.6.3 (Scope), 6.2 (Business/Mission Analysis)',
            'questions' => [
                [
                    'id' => 'objetivo_principal',
                    'question' => 'Qual é o principal objetivo do software? Por que ele está sendo desenvolvido ou modificado?',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'O principal objetivo é automatizar o gerenciamento de estoque para uma loja de eletrônicos online, reduzindo erros manuais e otimizando o reabastecimento. Ele está sendo desenvolvido porque o sistema atual é baseado em planilhas Excel, que não suportam o crescimento da loja, levando a atrasos em pedidos e perdas financeiras.'
                ],
                [
                    'id' => 'problema_negocio',
                    'question' => 'Qual problema de negócio ou oportunidade o software resolve? (Ex.: Automatizar processos, melhorar eficiência, entrar em novo mercado?)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Resolve o problema de estoque excessivo ou insuficiente, que causa perdas de R$ 50.000 anuais em produtos perecíveis como baterias. A oportunidade é integrar com e-commerce para vendas em tempo real, aumentando as vendas em 20%.'
                ],
                [
                    'id' => 'escopo_funcional',
                    'question' => 'Descreva o escopo: O que o software fará e o que NÃO fará? (Ex.: Limites funcionais, integrações excluídas.)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'O software fará rastreamento de estoque, alertas de reabastecimento, relatórios de vendas e integração com fornecedores. NÃO fará gerenciamento financeiro completo (ex.: faturamento) nem logística de entrega.'
                ],
                [
                    'id' => 'relacao_sistemas',
                    'question' => 'Como o software se relaciona com sistemas existentes ou futuros? (Ex.: É uma parte de um sistema maior?)',
                    'type' => 'textarea',
                    'required' => false,
                    'example' => 'Integra com o sistema de e-commerce existente (Shopify) para sincronizar vendas e com um futuro CRM para dados de clientes.'
                ],
                [
                    'id' => 'metas_mensuraveis',
                    'question' => 'Quais são as metas mensuráveis de sucesso? (Ex.: Reduzir tempo de processamento em X%, aumentar vendas em Y%.)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Reduzir erros de estoque em 90%, processar 1.000 pedidos/dia sem atrasos e aumentar a precisão de previsões de demanda para 85%.'
                ],
                [
                    'id' => 'prazo_milestones',
                    'question' => 'Qual é o prazo esperado para o projeto? Há milestones chave?',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Prazo total de 6 meses, com milestones: Requisitos finalizados em 1 mês, protótipo em 3 meses e lançamento em 6 meses.'
                ]
            ]
        ],
        'contexto_ambiente' => [
            'title' => '2. Contexto de Negócios e Ambiente',
            'description' => 'Base: 9.6.4 (Product Perspective), 6.2.3 (Problem/Opportunity Space), 9.6.7 (Limitations)',
            'questions' => [
                [
                    'id' => 'historico_projeto',
                    'question' => 'Qual é o histórico do projeto? Há sistemas semelhantes ou legados que influenciam isso?',
                    'type' => 'textarea',
                    'required' => false,
                    'example' => 'A loja opera há 5 anos com planilhas manuais. Sistemas legados incluem um antigo ERP desatualizado, que influenciará a migração de dados.'
                ],
                [
                    'id' => 'ambiente_operacional',
                    'question' => 'Descreva o ambiente operacional: Onde e como o software será usado? (Ex.: Online/offline, mobile/desktop, em campo ou escritório?)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Usado em desktop e mobile por gerentes em armazéns e escritório remoto, com acesso 24/7 via nuvem, em um ambiente com internet estável.'
                ],
                [
                    'id' => 'restricoes_externas',
                    'question' => 'Quais são as restrições externas? (Ex.: Regulamentações legais, padrões de indústria, limitações orçamentárias ou de hardware?)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Conformidade com LGPD para dados de fornecedores; orçamento de R$ 200.000; hardware limitado a PCs Windows e Android.'
                ],
                [
                    'id' => 'dependencias',
                    'question' => 'Há dependências de outros projetos, equipes ou fornecedores?',
                    'type' => 'textarea',
                    'required' => false,
                    'example' => 'Dependente de API de fornecedores como Amazon para integração de estoque; equipe de TI interna para suporte.'
                ],
                [
                    'id' => 'riscos_conhecidos',
                    'question' => 'Quais são os riscos conhecidos? (Ex.: Mudanças no mercado, dependência de tecnologia específica?)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Risco de downtime durante migração de dados (probabilidade 30%); mudanças no mercado de eletrônicos afetando demandas.'
                ],
                [
                    'id' => 'impacto_negocio',
                    'question' => 'Como o software impacta o negócio? (Ex.: Custos operacionais, ROI esperado.)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Reduz custos operacionais em 15% e melhora a satisfação do cliente com entregas mais rápidas, visando ROI em 12 meses.'
                ]
            ]
        ],
        'stakeholders_usuarios' => [
            'title' => '3. Stakeholders e Usuários',
            'description' => 'Base: 5.2.2 (Stakeholders), 9.6.5 (User Classes), 9.6.6 (User Characteristics), 6.3 (Stakeholder Needs)',
            'questions' => [
                [
                    'id' => 'principais_stakeholders',
                    'question' => 'Quem são os principais stakeholders? (Ex.: Clientes internos/externos, gerentes, usuários finais, reguladores.)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Proprietário da loja (acquirer), gerentes de estoque (usuários principais), fornecedores (externos) e equipe de vendas (interna).'
                ],
                [
                    'id' => 'classes_usuarios',
                    'question' => 'Descreva as classes de usuários: Quem usará o software? (Ex.: Perfis como "usuário iniciante", "administrador", "mantenedor". Inclua número de usuários, níveis de habilidade, localização.)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Classe 1: Gerentes (10 usuários, experts em logística); Classe 2: Vendedores (20 usuários, básicos em TI); Classe 3: Administradores (2 usuários, técnicos).'
                ],
                [
                    'id' => 'necessidades_expectativas',
                    'question' => 'Quais são as necessidades e expectativas de cada classe de usuário? (Ex.: Facilidade de uso, acessibilidade para deficientes?)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Gerentes: Relatórios em tempo real; Vendedores: Interface simples para consultas; Administradores: Ferramentas de backup e segurança.'
                ],
                [
                    'id' => 'interacao_usuarios',
                    'question' => 'Como os usuários interagem com o software? (Ex.: Interfaces de usuário, fluxos de trabalho diários.)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Via dashboard web/mobile: Gerentes atualizam estoque, vendedores verificam disponibilidade, admins gerenciam acessos.'
                ],
                [
                    'id' => 'treinamento_caracteristicas',
                    'question' => 'Há treinamento necessário? Quais são as características dos usuários (ex.: Nível educacional, experiência técnica)?',
                    'type' => 'textarea',
                    'required' => false,
                    'example' => 'Sim, treinamento de 2 dias. Usuários: Idade 25-45, ensino médio/superior, experiência em Excel, mas pouca em software avançado.'
                ],
                [
                    'id' => 'aprovacao_mudancas',
                    'question' => 'Quem aprova mudanças nos requisitos? Há hierarquia de priorização?',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Proprietário aprova; priorização: Alta para funções críticas como alertas de estoque.'
                ]
            ]
        ],
        'requisitos_funcionais' => [
            'title' => '4. Requisitos Funcionais',
            'description' => 'Base: 9.6.10 (Specified Requirements), 9.6.12 (Functions), 6.4 (System/Software Requirements)',
            'questions' => [
                [
                    'id' => 'principais_funcoes',
                    'question' => 'Quais são as principais funções que o software deve realizar? (Ex.: Login, processamento de dados, relatórios.)',
                    'type' => 'dynamic_functions',
                    'required' => true,
                    'example' => 'Adicione funcionalidades como: Login de usuários, Processamento de pedidos, Geração de relatórios, etc.'
                ],
                [
                    'id' => 'dados_manipulados',
                    'question' => 'Quais dados o software manipula? (Ex.: Tipos de dados, volumes, retenção.)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Dados de produtos (ID, quantidade, preço), retenção de 5 anos em banco de dados SQL.'
                ],
                [
                    'id' => 'cenarios_operacionais',
                    'question' => 'Descreva cenários operacionais: Normais, de erro, de pico (ex.: "O que acontece em falha de rede?").',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Normal: Venda diária; Erro: Falha de integração (notificação por email); Pico: Black Friday (suportar 5x o tráfego normal).'
                ]
            ]
        ],
        'requisitos_nao_funcionais' => [
            'title' => '5. Requisitos Não-Funcionais',
            'description' => 'Base: 9.6.13 (Usability), 9.6.14 (Performance), 9.6.15 (Database), 9.6.18 (Attributes)',
            'questions' => [
                [
                    'id' => 'requisitos_performance',
                    'question' => 'Quais são os requisitos de performance? (Ex.: Tempo de resposta < 2s, throughput de 100 transações/min.)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Tempo de resposta < 1s para consultas; throughput de 500 atualizações/hora.'
                ],
                [
                    'id' => 'requisitos_usabilidade',
                    'question' => 'Requisitos de usabilidade: O software deve ser intuitivo? Suporte a múltiplos idiomas/acessibilidade?',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Sim, interface responsiva, suporte a português/inglês, acessível para daltônicos.'
                ],
                [
                    'id' => 'requisitos_confiabilidade',
                    'question' => 'Requisitos de confiabilidade: Taxa de uptime (ex.: 99.9%), recuperação de falhas?',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => '99% uptime, recuperação automática em < 5 min.'
                ],
                [
                    'id' => 'requisitos_seguranca',
                    'question' => 'Requisitos de segurança: Autenticação, criptografia, proteção contra ameaças? (Ex.: Conformidade com GDPR.)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Login com 2FA, criptografia AES para dados sensíveis, conformidade com LGPD.'
                ],
                [
                    'id' => 'requisitos_escalabilidade',
                    'question' => 'Requisitos de escalabilidade/portabilidade: Deve rodar em quais plataformas? Suportar crescimento de usuários?',
                    'type' => 'textarea',
                    'required' => false,
                    'example' => 'Escalar para 100 usuários simultâneos; rodar em Windows, Linux e mobile browsers.'
                ],
                [
                    'id' => 'requisitos_manutencao',
                    'question' => 'Requisitos de manutenção: Facilidade de atualizações, logs para depuração?',
                    'type' => 'textarea',
                    'required' => false,
                    'example' => 'Logs detalhados, atualizações sem downtime.'
                ]
            ]
        ],
        'interfaces_integracoes' => [
            'title' => '6. Interfaces e Integrações',
            'description' => 'Base: 9.6.11 (External Interfaces), 9.6.4 (Interfaces)',
            'questions' => [
                [
                    'id' => 'interfaces_externas',
                    'question' => 'Quais interfaces externas o software precisa? (Ex.: APIs, bancos de dados, hardware, outros sistemas.)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'API REST para Shopify, banco de dados MySQL.'
                ],
                [
                    'id' => 'formatos_dados',
                    'question' => 'Descreva formatos de dados: Entradas/saídas, protocolos (ex.: JSON, HTTP).',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Inputs: JSON para produtos; Outputs: CSV para relatórios.'
                ],
                [
                    'id' => 'integracoes_terceiros',
                    'question' => 'Há integrações com serviços de terceiros? (Ex.: Pagamentos, nuvem como AWS.)',
                    'type' => 'textarea',
                    'required' => false,
                    'example' => 'Sim, com API de fornecedores para pedidos automáticos.'
                ],
                [
                    'id' => 'requisitos_comunicacao',
                    'question' => 'Quais são os requisitos de comunicação? (Ex.: Redes, latência.)',
                    'type' => 'textarea',
                    'required' => false,
                    'example' => 'HTTPS seguro, latência < 200ms.'
                ]
            ]
        ],
        'suposicoes_verificacao' => [
            'title' => '7. Suposições, Dependências e Verificação',
            'description' => 'Base: 9.6.8 (Assumptions), 9.6.19 (Verification), 6.5 (Verification/Validation)',
            'questions' => [
                [
                    'id' => 'suposicoes_ambiente',
                    'question' => 'Quais suposições você faz sobre o ambiente ou usuários? (Ex.: "Usuários têm internet estável.")',
                    'type' => 'textarea',
                    'required' => false,
                    'example' => 'Usuários têm conexão estável; estoque inicial será migrado manualmente.'
                ],
                [
                    'id' => 'dependencias_externas',
                    'question' => 'Quais dependências externas existem? (Ex.: Bibliotecas de software, hardware específico.)',
                    'type' => 'textarea',
                    'required' => false,
                    'example' => 'Dependente de biblioteca Pandas para relatórios.'
                ],
                [
                    'id' => 'verificacao_requisitos',
                    'question' => 'Como verificaremos se o software atende aos requisitos? (Ex.: Testes unitários, aceitação do usuário.)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Testes unitários (80% cobertura), testes de usuário final.'
                ],
                [
                    'id' => 'criterios_aceitacao',
                    'question' => 'Quais critérios de aceitação? (Ex.: Métricas para sucesso em testes.)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Aprovação em 95% dos cenários de teste sem bugs críticos.'
                ]
            ]
        ],
        'riscos_evolucao' => [
            'title' => '8. Riscos, Prioridades e Evolução',
            'description' => 'Base: 6.6 (Requirements Management), 5.2.8 (Attributes)',
            'questions' => [
                [
                    'id' => 'requisitos_prioritarios',
                    'question' => 'Quais requisitos são prioritários? (Ex.: Essenciais vs. desejáveis.)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Essenciais: Rastreamento de estoque; Desejáveis: Relatórios avançados.'
                ],
                [
                    'id' => 'planos_evolucao',
                    'question' => 'Há planos para evoluções futuras? (Ex.: Fases ou versões incrementais.)',
                    'type' => 'textarea',
                    'required' => false,
                    'example' => 'Sim, versão 2.0 com IA para previsões de demanda.'
                ],
                [
                    'id' => 'riscos_projeto',
                    'question' => 'Quais riscos você vê no projeto? (Ex.: Mudanças frequentes de requisitos - "requirements creep".)',
                    'type' => 'textarea',
                    'required' => true,
                    'example' => 'Mudanças nos requisitos de fornecedores (risco médio); atrasos em integração.'
                ],
                [
                    'id' => 'gerenciamento_mudancas',
                    'question' => 'Como gerenciaremos mudanças nos requisitos?',
                    'type' => 'textarea',
                    'required' => false,
                    'example' => 'Via ferramenta Jira, com aprovações do proprietário e rastreamento de versões.'
                ]
            ]
        ]
    ];

    public function index()
    {
        return view('agente-srs2.index', [
            'questionCategories' => $this->questions
        ]);
    }

    public function gerarSRS(Request $request)
    {
        // Aumentar limite de tempo para processar requisições grandes
        set_time_limit(120);
        
        // Validar todas as respostas obrigatórias
        $rules = [];
        foreach ($this->questions as $categoryKey => $category) {
            foreach ($category['questions'] as $question) {
                if ($question['required']) {
                    if (isset($question['type']) && $question['type'] === 'dynamic_functions') {
                        // Para campos dinâmicos, validar JSON e conteúdo
                        $rules["answers.{$categoryKey}.{$question['id']}"] = [
                            'required',
                            'string',
                            function ($attribute, $value, $fail) {
                                if (empty($value)) {
                                    $fail('Este campo é obrigatório.');
                                    return;
                                }
                                
                                try {
                                    $functionalities = json_decode($value, true);
                                    if (json_last_error() !== JSON_ERROR_NONE) {
                                        $fail('Formato de dados inválido. Erro JSON: ' . json_last_error_msg());
                                        return;
                                    }
                                    
                                    if (!is_array($functionalities) || empty($functionalities)) {
                                        $fail('Você deve adicionar pelo menos uma funcionalidade.');
                                        return;
                                    }
                                    
                                    foreach ($functionalities as $func) {
                                        if (!isset($func['name']) || empty($func['name']) || trim($func['name']) === '') {
                                            $fail('Todas as funcionalidades devem ter um nome.');
                                            return;
                                        }
                                    }
                                } catch (\Exception $e) {
                                    $fail('Erro na validação: ' . $e->getMessage());
                                }
                            }
                        ];
                    } else {
                        $rules["answers.{$categoryKey}.{$question['id']}"] = 'required|string';
                    }
                }
            }
        }

        $request->validate($rules, [
            'required' => 'Esta pergunta é obrigatória.'
        ]);

        $answers = $request->answers;
        
        // Formatar as respostas para enviar à API
        $formattedAnswers = $this->formatAnswersForAPI($answers);

        try {
            $apiKey = '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';
            
            $response = Http::timeout(60)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $this->getSRSGenerationPrompt()
                    ],
                    [
                        'role' => 'user',
                        'content' => "Com base nas seguintes respostas estruturadas segundo ISO/IEC/IEEE 29148:2018, gere um SRS completo:\n\n{$formattedAnswers}"
                    ]
                ],
                'max_tokens' => 4000
            ]);

            if ($response->successful()) {
                $srsDocument = $response->json()['choices'][0]['message']['content'];
                
                // Salvar no histórico
                try {
                    SrsHistory::create([
                        'session_id' => session()->getId(),
                        'version' => 'v2',
                        'answers' => $answers,
                        'srs_document' => $srsDocument,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]);
                } catch (\Exception $e) {
                    // Log error but don't fail the request
                    \Log::error('Failed to save SRS history: ' . $e->getMessage());
                }
                
                Session::put('srs2_document', $srsDocument);
                Session::put('srs2_answers', $answers);

                return response()->json([
                    'success' => true,
                    'srs' => $srsDocument
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Erro ao gerar o SRS. Status: ' . $response->status()
                ], 400);
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro na requisição à API OpenAI: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao processar requisição: ' . $e->getMessage()
            ], 500);
        }
    }

    private function formatAnswersForAPI($answers)
    {
        $formatted = "";
        
        foreach ($this->questions as $categoryKey => $category) {
            $formatted .= "## {$category['title']}\n\n";
            
            foreach ($category['questions'] as $question) {
                $answer = $answers[$categoryKey][$question['id']] ?? 'Não respondido';
                
                // Handle dynamic functions specially
                if ($question['type'] === 'dynamic_functions' && $question['id'] === 'principais_funcoes') {
                    $formatted .= "**{$question['question']}**\n";
                    
                    try {
                        $functionalities = json_decode($answer, true);
                        if (is_array($functionalities)) {
                            foreach ($functionalities as $index => $func) {
                                $formatted .= "\n### Funcionalidade " . ($index + 1) . ": {$func['name']}\n";
                                
                                if (!empty($func['workflow'])) {
                                    $formatted .= "**Fluxo de trabalho:**\n{$func['workflow']}\n";
                                }
                                
                                if (!empty($func['sequences'])) {
                                    $formatted .= "**Sequências de operações:**\n{$func['sequences']}\n";
                                }
                            }
                            $formatted .= "\n";
                        } else {
                            $formatted .= "{$answer}\n\n";
                        }
                    } catch (\Exception $e) {
                        $formatted .= "{$answer}\n\n";
                    }
                } else {
                    $formatted .= "**{$question['question']}**\n";
                    $formatted .= "{$answer}\n\n";
                }
            }
        }
        
        return $formatted;
    }

    private function getSRSGenerationPrompt()
    {
        return '# Papel
Você é um arquiteto de software sênior especializado em documentação de requisitos conforme ISO/IEC/IEEE 29148:2018.

# Contexto
Você recebeu respostas estruturadas de uma entrevista de elicitação de requisitos baseada nas 8 categorias principais do padrão ISO 29148.

# Tarefa
Gere um Software Requirements Specification (SRS) completo, profissional e detalhado seguindo rigorosamente o padrão ISO/IEC/IEEE 29148:2018.

# Estrutura Obrigatória do SRS

## 1. INTRODUÇÃO
### 1.1 Propósito
- Objetivo deste documento SRS
- Audiência pretendida
- Escopo do documento

### 1.2 Escopo do Produto
- Nome oficial do software
- O que o software FARÁ (funcionalidades incluídas)
- O que o software NÃO FARÁ (exclusões explícitas)
- Benefícios, objetivos e metas

### 1.3 Definições, Acrônimos e Abreviações
- Termos técnicos utilizados
- Acrônimos do domínio

### 1.4 Referências
- Documentos relacionados
- Padrões aplicáveis

### 1.5 Visão Geral do Documento
- Organização do SRS

## 2. DESCRIÇÃO GERAL

### 2.1 Perspectiva do Produto
- Contexto do sistema
- Interfaces com outros sistemas
- Restrições de memória
- Operações
- Requisitos de adaptação

### 2.2 Funções do Produto
- Lista resumida das principais funções
- Organizada por módulos ou subsistemas

### 2.3 Características dos Usuários
- Classes de usuários detalhadas
- Educação, experiência, expertise técnica
- Permissões e restrições por classe

### 2.4 Restrições
- Regulamentares e políticas
- Limitações de hardware
- Interfaces com outras aplicações
- Operações paralelas
- Funções de auditoria
- Funções de controle
- Requisitos de linguagem
- Protocolos de comunicação
- Requisitos de confiabilidade
- Criticalidade da aplicação
- Considerações de segurança

### 2.5 Suposições e Dependências
- Fatores que afetam os requisitos
- Dependências externas

## 3. REQUISITOS ESPECÍFICOS

### 3.1 Requisitos de Interface Externa

#### 3.1.1 Interfaces de Usuário
- Características lógicas de cada interface
- Aspectos de usabilidade
- Padrões de GUI
- Layout de telas

#### 3.1.2 Interfaces de Hardware
- Características do hardware
- Dispositivos suportados

#### 3.1.3 Interfaces de Software
- APIs e bibliotecas
- Sistemas operacionais
- Outros softwares

#### 3.1.4 Interfaces de Comunicação
- Protocolos
- Formatos de mensagem

### 3.2 Requisitos Funcionais
[Para cada função principal, use este formato:]

#### RF-XXX: [Nome da Função]
- **Descrição**: [Detalhada]
- **Entradas**: [Dados necessários]
- **Processamento**: [Lógica/algoritmo]
- **Saídas**: [Resultados esperados]
- **Prioridade**: Alta/Média/Baixa
- **Dependências**: [Outros requisitos]
- **Critérios de Aceitação**:
  1. [Condição verificável]
  2. [Condição verificável]

### 3.3 Requisitos de Desempenho
- Número de usuários simultâneos
- Quantidade de informações
- Tempos de resposta
- Throughput
- Benchmarks

### 3.4 Requisitos de Banco de Dados
- Tipos de informação
- Frequência de uso
- Capacidades de acesso
- Integridade dos dados
- Retenção

### 3.5 Restrições de Design
- Padrões de conformidade
- Limitações de hardware

### 3.6 Atributos do Sistema

#### 3.6.1 Confiabilidade
- MTBF
- MTTR
- Taxa de disponibilidade

#### 3.6.2 Segurança
- Autenticação
- Autorização
- Criptografia
- Auditoria

#### 3.6.3 Manutenibilidade
- Modularidade
- Documentação

#### 3.6.4 Portabilidade
- Plataformas suportadas
- Adaptações necessárias

### 3.7 Requisitos de Verificação
- Métodos de verificação para cada requisito
- Critérios de aceitação
- Casos de teste de alto nível

## 4. INFORMAÇÕES DE SUPORTE

### 4.1 Índice
### 4.2 Apêndices
- Modelos de análise
- Casos de uso detalhados
- Diagramas

# Instruções de Geração

1. **Use TODAS as respostas fornecidas** - Não ignore nenhuma informação
2. **Seja ESPECÍFICO e MENSURÁVEL** - Evite requisitos vagos
3. **Mantenha CONSISTÊNCIA** - Use terminologia uniforme
4. **Gere IDs únicos** - RF-001, RF-002, RNF-001, etc.
5. **Priorize requisitos** - Alta/Média/Baixa baseado nas respostas
6. **Adicione critérios de aceitação** - Para cada requisito funcional
7. **Preencha lacunas** - Marque como "A ser definido" se necessário
8. **Use linguagem profissional** - Clara e técnica quando apropriado
9. **Formate em Markdown** - Com hierarquia clara e tabelas quando útil
10. **Garanta rastreabilidade** - Conecte requisitos entre si

# Qualidade do Output
- O SRS deve estar pronto para ser usado por desenvolvedores
- Deve servir como contrato entre stakeholders
- Deve permitir estimativas precisas de esforço
- Deve ser verificável e testável';
    }

    public function sugerirResposta(Request $request)
    {
        $request->validate([
            'current_category' => 'required|string',
            'current_question' => 'required|string',
            'context' => 'required|array'
        ]);

        $category = $request->current_category;
        $questionId = $request->current_question;
        $context = $request->context;
        $isDynamicFunctions = $request->input('is_dynamic_functions', false);
        
        // Encontrar a pergunta atual
        $currentQuestion = null;
        foreach ($this->questions[$category]['questions'] as $q) {
            if ($q['id'] === $questionId) {
                $currentQuestion = $q;
                break;
            }
        }

        if (!$currentQuestion) {
            return response()->json(['success' => false, 'error' => 'Pergunta não encontrada'], 404);
        }

        // Se for para sugerir funcionalidades dinâmicas
        if ($isDynamicFunctions && $questionId === 'principais_funcoes') {
            return $this->sugerirFuncionalidades($context);
        }

        // Formatar contexto para a IA
        $contextString = $this->formatContextForAI($context, $currentQuestion);
        
        // Adicionar exemplo se existir
        $exampleText = '';
        if (isset($currentQuestion['example']) && !empty($currentQuestion['example'])) {
            $exampleText = "\n\nEXEMPLO DE RESPOSTA ESPERADA:\n" . $currentQuestion['example'] . "\n\nUse este exemplo como referência para o formato e nível de detalhe esperado, mas adapte o conteúdo ao contexto específico do projeto.";
        }

        try {
            $apiKey = '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';
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Você é um especialista em elicitação de requisitos seguindo ISO/IEC/IEEE 29148:2018.
                        
Sua tarefa é sugerir uma resposta CLARA, ESPECÍFICA e NÃO AMBÍGUA para a pergunta atual, baseando-se nas respostas anteriores.

PRINCÍPIOS FUNDAMENTAIS - Evite ambiguidades seguindo estas regras:

1. **ESPECIFICIDADE TOTAL**
   ❌ AMBÍGUO: "O sistema deve ser rápido"
   ✅ ESPECÍFICO: "O sistema deve processar requisições em no máximo 2 segundos para 95% dos casos"

2. **VALORES MENSURÁVEIS**
   ❌ AMBÍGUO: "Muitos usuários simultâneos"
   ✅ ESPECÍFICO: "Suportar 500 usuários simultâneos com degradação máxima de 10% no tempo de resposta"

3. **AÇÕES CONCRETAS**
   ❌ AMBÍGUO: "Melhorar a experiência do usuário"
   ✅ ESPECÍFICO: "Reduzir o número de cliques para completar uma tarefa de 8 para 3, implementar autocompletar em formulários"

4. **PRAZOS DEFINIDOS**
   ❌ AMBÍGUO: "Em breve"
   ✅ ESPECÍFICO: "Entrega em 3 meses - MVP em 6 semanas, versão completa em 12 semanas"

5. **TECNOLOGIAS EXPLÍCITAS**
   ❌ AMBÍGUO: "Usar tecnologias modernas"
   ✅ ESPECÍFICO: "React 18 para frontend, Node.js 20 com Express para API REST, PostgreSQL 15 para banco de dados"

6. **FUNCIONALIDADES DETALHADAS**
   ❌ AMBÍGUO: "Sistema de login"
   ✅ ESPECÍFICO: "Autenticação via email/senha com 2FA obrigatório, recuperação por email, bloqueio após 5 tentativas falhas"

DIRETRIZES PARA A RESPOSTA:
- Use listas numeradas quando apropriado
- Inclua critérios de aceitação quando relevante
- Forneça exemplos concretos
- Defina limites e exceções
- Use métricas quantificáveis
- Especifique tecnologias, versões e padrões
- Mantenha consistência com respostas anteriores
- Para perguntas sobre riscos/problemas, liste itens específicos com probabilidade e impacto

FORMATO: Forneça APENAS o texto da resposta, sem preâmbulos ou explicações.' . $exampleText
                    ],
                    [
                        'role' => 'user',
                        'content' => $contextString
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);

            if ($response->successful()) {
                $suggestion = $response->json()['choices'][0]['message']['content'];
                
                return response()->json([
                    'success' => true,
                    'suggestion' => trim($suggestion)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Erro ao gerar sugestão'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao conectar com a API: ' . $e->getMessage()
            ], 500);
        }
    }

    private function sugerirFuncionalidades($context)
    {
        $contextString = $this->formatContextForAI($context, ['question' => 'Sugerir funcionalidades principais']);
        
        try {
            $apiKey = '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';
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Você é um especialista em engenharia de requisitos seguindo ISO/IEC/IEEE 29148:2018.

Baseando-se no contexto do projeto fornecido, sugira 3-5 funcionalidades principais que o sistema deve ter.

Para CADA funcionalidade, forneça:
1. **Nome**: Um nome claro e descritivo (ex: "Autenticação de Usuários", "Gestão de Estoque")
2. **Fluxo de Trabalho**: Como o usuário interage, inputs e outputs esperados
3. **Sequências de Operações**: Passos específicos do processo (opcional mas recomendado)

IMPORTANTE:
- Seja ESPECÍFICO e evite generalidades
- Base-se no contexto fornecido sobre o projeto
- Cada funcionalidade deve ser independente e completa
- Use exemplos concretos relacionados ao domínio do projeto
- Fluxos devem incluir ações do usuário e respostas do sistema

Retorne APENAS um JSON válido no formato:
{
  "functionalities": [
    {
      "name": "Nome da Funcionalidade",
      "workflow": "Descrição detalhada do fluxo...",
      "sequences": "Passo 1 > Passo 2 > Passo 3..."
    }
  ]
}'
                    ],
                    [
                        'role' => 'user',
                        'content' => $contextString . "\n\nCom base neste contexto, sugira as funcionalidades principais que o sistema deve ter."
                    ]
                ],
                'temperature' => 0.7,
                'response_format' => ['type' => 'json_object']
            ]);

            if ($response->successful()) {
                $content = json_decode($response->json()['choices'][0]['message']['content'], true);
                
                if (isset($content['functionalities']) && is_array($content['functionalities'])) {
                    return response()->json([
                        'success' => true,
                        'functionalities' => $content['functionalities']
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'error' => 'Formato de resposta inválido'
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Erro ao gerar sugestões'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao conectar com a API: ' . $e->getMessage()
            ], 500);
        }
    }

    private function formatContextForAI($context, $currentQuestion)
    {
        $formatted = "CONTEXTO DO PROJETO:\n\n";
        
        // Adicionar respostas anteriores relevantes
        foreach ($this->questions as $catKey => $category) {
            $hasAnswers = false;
            $categoryAnswers = "";
            
            foreach ($category['questions'] as $q) {
                if (isset($context[$catKey][$q['id']]) && !empty($context[$catKey][$q['id']])) {
                    if (!$hasAnswers) {
                        $categoryAnswers .= "## {$category['title']}\n\n";
                        $hasAnswers = true;
                    }
                    
                    // Handle dynamic functions specially
                    if ($q['type'] === 'dynamic_functions' && $q['id'] === 'principais_funcoes') {
                        $categoryAnswers .= "**{$q['question']}**\n";
                        try {
                            $functionalities = json_decode($context[$catKey][$q['id']], true);
                            if (is_array($functionalities)) {
                                foreach ($functionalities as $index => $func) {
                                    $categoryAnswers .= "\n### Funcionalidade " . ($index + 1) . ": {$func['name']}\n";
                                    if (!empty($func['workflow'])) {
                                        $categoryAnswers .= "**Fluxo:** {$func['workflow']}\n";
                                    }
                                    if (!empty($func['sequences'])) {
                                        $categoryAnswers .= "**Sequências:** {$func['sequences']}\n";
                                    }
                                }
                                $categoryAnswers .= "\n";
                            } else {
                                $categoryAnswers .= "{$context[$catKey][$q['id']]}\n\n";
                            }
                        } catch (\Exception $e) {
                            $categoryAnswers .= "{$context[$catKey][$q['id']]}\n\n";
                        }
                    } else {
                        $categoryAnswers .= "**{$q['question']}**\n";
                        $categoryAnswers .= "{$context[$catKey][$q['id']]}\n\n";
                    }
                }
            }
            
            if ($hasAnswers) {
                $formatted .= $categoryAnswers;
            }
        }
        
        $formatted .= "\n---\n\nPERGUNTA ATUAL QUE PRECISA DE SUGESTÃO:\n";
        $formatted .= "**{$currentQuestion['question']}**\n\n";
        $formatted .= "Por favor, sugira uma resposta apropriada baseada no contexto acima.";
        
        return $formatted;
    }

    public function downloadSRS(Request $request)
    {
        // Check if downloading from history
        if ($request->has('history_id')) {
            $historico = SrsHistory::findOrFail($request->history_id);
            
            // Verify it's the correct version
            if ($historico->version !== 'v2') {
                abort(404);
            }
            
            $srsDocument = $historico->srs_document;
            $filename = 'srs_historico_' . $historico->id . '_' . date('Y-m-d_H-i-s') . '.md';
        } else {
            // Download from session
            $srsDocument = Session::get('srs2_document', '');
            
            if (empty($srsDocument)) {
                return redirect()->route('agente-srs2.index')->with('error', 'Nenhum documento SRS disponível para download.');
            }
            
            $filename = 'srs_document_iso_' . date('Y-m-d_H-i-s') . '.md';
        }
        
        return response($srsDocument)
            ->header('Content-Type', 'text/markdown')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    
    public function historico()
    {
        $historicos = SrsHistory::where('version', 'v2')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('agente-srs2.historico', compact('historicos'));
    }
    
    public function verHistorico($id)
    {
        $historico = SrsHistory::findOrFail($id);
        
        // Verificar se é da versão correta
        if ($historico->version !== 'v2') {
            abort(404);
        }
        
        return view('agente-srs2.ver-historico', compact('historico'));
    }
}