<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\SrsHistory;

class AgenteSRSController extends Controller
{
    private $openaiApiKey;
    private $fixedQuestions = [
        [
            "section" => "0. Problema",
            "subsection" => null,
            "question" => "Descreva o problema ou oportunidade que o produto ou funcionalidade vai abordar."
        ],
        [
            "section" => "1. Público-Alvo",
            "subsection" => null,
            "question" => "Descreva o público-alvo específico para o produto ou funcionalidade."
        ],
        [
            "section" => "2. Dados de Suporte",
            "subsection" => null,
            "question" => "Inclua qualquer informação que reforce o argumento de por que criar esse produto ou funcionalidade."
        ],
        [
            "section" => "4. Solução",
            "subsection" => null,
            "question" => "Descreva como o seu produto vai solucionar esse problema."
        ],
        [
            "section" => "5. Sistemas de Referência",
            "subsection" => null,
            "question" => "Existem sistemas ou plataformas que você gostaria que fossem analisadas como referência?"
        ]
    ];

    public function __construct()
    {
        $this->openaiApiKey = env('OPENAI_API_KEY');
    }

    public function index()
    {
        return view('agente-srs.index');
    }

    public function enriquecerDescricao(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string',
            'project_summary' => 'required|string|min:10'
        ]);

        $apiKey = $request->api_key;
        $projectSummary = $request->project_summary;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => '# Papel
Você é um analista de requisitos sênior com 15+ anos de experiência em engenharia de software e especificação de sistemas complexos.

# Tarefa
Enriqueça a descrição do projeto fornecida, expandindo-a com detalhes técnicos e funcionais relevantes.

# Diretrizes de Análise
1. **Funcionalidades Principais**: Detalhe cada funcionalidade com:
   - Objetivo específico
   - Fluxo de operação
   - Dados envolvidos
   - Atores/usuários

2. **Integrações e APIs**: Identifique:
   - Sistemas externos necessários
   - Protocolos de comunicação (REST, GraphQL, WebSocket)
   - Formatos de dados (JSON, XML)
   - Autenticação/autorização

3. **Requisitos Não-Funcionais**: Especifique:
   - Performance: tempos de resposta, throughput
   - Segurança: LGPD, criptografia, auditoria
   - Escalabilidade: usuários simultâneos, volume de dados
   - Usabilidade: acessibilidade, responsividade

4. **Stack Técnica**: Sugira:
   - Linguagens e frameworks apropriados
   - Banco de dados (SQL/NoSQL)
   - Infraestrutura (cloud, on-premise)
   - Ferramentas de DevOps

5. **Casos de Uso**: Liste cenários reais:
   - Fluxo principal (happy path)
   - Fluxos alternativos
   - Casos de erro

# Formato de Saída
- PRESERVE todo o texto original do usuário
- ADICIONE detalhes em parágrafos separados
- USE linguagem técnica mas acessível
- ORGANIZE as informações de forma lógica
- MANTENHA coerência com o contexto do projeto

# Exemplo de Estrutura
[Texto original do usuário]

**Detalhamento Funcional:**
[Suas adições sobre funcionalidades]

**Arquitetura e Integrações:**
[Suas adições técnicas]

**Requisitos de Qualidade:**
[Suas adições sobre requisitos não-funcionais]

**Cenários de Uso:**
[Suas adições sobre casos de uso]'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Enriqueça esta descrição de projeto: {$projectSummary}"
                    ]
                ]
            ]);

            if ($response->successful()) {
                $enrichedDescription = $response->json()['choices'][0]['message']['content'];
                
                return response()->json([
                    'success' => true,
                    'enrichedDescription' => $enrichedDescription
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Erro ao enriquecer descrição. Verifique sua chave API.'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao conectar com a API: ' . $e->getMessage()
            ], 500);
        }
    }

    public function gerarPerguntas(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string',
            'project_summary' => 'required|string|min:10'
        ]);

        $apiKey = $request->api_key;
        $projectSummary = $request->project_summary;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => '# Papel
Você é um engenheiro de requisitos certificado com expertise em ISO/IEC/IEEE 29148:2018 e experiência em projetos de software complexos.

# Contexto
Você está conduzindo uma sessão de elicitação de requisitos para criar um documento SRS completo e detalhado.

# Tarefa Principal
Gere perguntas estratégicas que extraiam informações COMPLETAS e DETALHADAS sobre o projeto, seguindo rigorosamente a estrutura ISO 29148.

# Estrutura Obrigatória do SRS (ISO 29148)
1. **Introdução**
   1.1 Propósito
   1.2 Escopo
   1.3 Perspectiva do Produto
   1.4 Funções do Produto
   1.5 Características dos Usuários
   1.6 Restrições
   1.7 Premissas e Dependências

2. **Requisitos**
   2.1 Requisitos de Interface Externa
   2.2 Requisitos Funcionais
   2.3 Requisitos de Usabilidade
   2.4 Requisitos de Performance
   2.5 Requisitos de Banco de Dados
   2.6 Restrições de Design
   2.7 Atributos do Sistema

3. **Verificação**
   3.1 Métodos de Verificação
   3.2 Critérios de Aceitação

4. **Informações de Suporte**
   4.1 Apêndices
   4.2 Referências

# Diretrizes para Formulação de Perguntas

## Técnicas de Questionamento
- Use perguntas ABERTAS para contexto: "Como você imagina..."
- Use perguntas ESPECÍFICAS para detalhes: "Quais exatamente..."
- Use perguntas de VALIDAÇÃO: "É correto afirmar que..."
- Use perguntas de PRIORIZAÇÃO: "Em ordem de importância..."

## Regras para Sugestões
1. **Perguntas Técnicas** (funcionalidades, integrações, tecnologias):
   - MÍNIMO 5-7 sugestões práticas e relevantes
   - Ordene por popularidade/relevância
   - Inclua opções modernas e tradicionais
   
2. **Perguntas de Contexto**:
   - 3-4 sugestões exemplificativas
   - Cubra diferentes cenários

3. **Qualidade das Sugestões**:
   - Específicas ao domínio do projeto
   - Tecnicamente viáveis
   - Alinhadas com práticas atuais do mercado

# Formato JSON Obrigatório
{
  "questions": [
    {
      "section": "2. Requisitos",
      "subsection": "2.2 Requisitos Funcionais",
      "question": "Quais funcionalidades de autenticação o sistema deve suportar?",
      "suggestions": [
        "Login com email e senha",
        "Autenticação em dois fatores (2FA)",
        "Login social (Google, Facebook, Apple)",
        "Single Sign-On (SSO) corporativo",
        "Autenticação biométrica",
        "Login com certificado digital",
        "Recuperação de senha por email/SMS"
      ]
    }
  ]
}

# Instruções Especiais
1. ANALISE o resumo do projeto para personalizar perguntas
2. PRIORIZE perguntas que revelam requisitos ocultos
3. INCLUA perguntas sobre edge cases e cenários de erro
4. GARANTA cobertura completa de requisitos não-funcionais
5. FORMULE perguntas que evitem ambiguidade
6. Para CADA seção principal, gere 3-5 perguntas relevantes'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Resumo do projeto: {$projectSummary}"
                    ]
                ],
                'response_format' => ['type' => 'json_object']
            ]);

            if ($response->successful()) {
                $content = json_decode($response->json()['choices'][0]['message']['content'], true);
                $dynamicQuestions = $content['questions'] ?? [];
                
                // Combinar perguntas fixas com dinâmicas
                $allQuestions = array_merge($this->fixedQuestions, $dynamicQuestions);
                
                Session::put('srs_questions', $allQuestions);
                Session::put('srs_project_summary', $projectSummary);
                Session::put('srs_api_key', $apiKey);
                
                return response()->json([
                    'success' => true,
                    'questions' => $allQuestions
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Erro ao gerar perguntas. Verifique sua chave API.'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao conectar com a API: ' . $e->getMessage()
            ], 500);
        }
    }

    public function gerarSRS(Request $request)
    {
        $request->validate([
            'answers' => 'required|array'
        ]);

        $questions = Session::get('srs_questions', []);
        $projectSummary = Session::get('srs_project_summary', '');
        $apiKey = Session::get('srs_api_key', '');
        $answers = $request->answers;

        if (empty($questions) || empty($apiKey)) {
            return response()->json([
                'success' => false,
                'error' => 'Sessão expirada. Por favor, gere as perguntas novamente.'
            ], 400);
        }

        // Preparar pares de pergunta-resposta
        $qaPairs = [];
        foreach ($questions as $q) {
            $answer = $answers[$q['question']] ?? 'Não respondido';
            $qaPairs[] = "Pergunta: {$q['question']}\nResposta: {$answer}";
        }
        $qaString = implode("\n\n", $qaPairs);

        try {
            // Gerar SRS
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => '# Papel
Você é um arquiteto de software sênior e redator técnico especializado em documentação de requisitos conforme ISO/IEC/IEEE 29148:2018.

# Contexto
Você está criando um Software Requirements Specification (SRS) profissional que servirá como contrato entre stakeholders e equipe de desenvolvimento.

# Objetivo
Produzir um documento SRS completo, detalhado e de alta qualidade que:
- Elimine ambiguidades
- Seja verificável e rastreável
- Atenda padrões internacionais
- Sirva como base sólida para desenvolvimento

# Princípios de Requisitos de Qualidade (SMART)
**S**pecific: Detalhado e sem ambiguidade
**M**easurable: Quantificável e verificável
**A**chievable: Tecnicamente viável
**R**elevant: Alinhado aos objetivos
**T**ime-bound: Com prazos ou critérios temporais

# Estrutura Detalhada do SRS (ISO 29148)

## 1. INTRODUÇÃO
### 1.1 Propósito
- Objetivo do documento
- Audiência-alvo
- Uso pretendido

### 1.2 Escopo
- Nome do produto: [EXTRAIR DAS RESPOSTAS]
- O que o software FARÁ (funcionalidades principais)
- O que o software NÃO FARÁ (exclusões explícitas)
- Benefícios e objetivos

### 1.3 Perspectiva do Produto
- Contexto do sistema (novo ou substituição)
- Interfaces com outros sistemas
- Restrições operacionais
- Dependências

### 1.4 Funções do Produto
- Lista de alto nível das principais funcionalidades
- Organizada por prioridade (Alta/Média/Baixa)

### 1.5 Características dos Usuários
- Perfis detalhados (conhecimento técnico, experiência)
- Frequência de uso esperada
- Privilégios e restrições

### 1.6 Restrições
- Regulamentares (LGPD, etc.)
- Hardware/Software
- Políticas organizacionais
- Interfaces externas

### 1.7 Premissas e Dependências
- Fatores externos assumidos como verdadeiros
- Dependências de terceiros

## 2. REQUISITOS ESPECÍFICOS

### 2.1 Requisitos de Interface Externa
#### 2.1.1 Interfaces de Usuário
- Padrões de UI/UX
- Requisitos de acessibilidade (WCAG)
- Dispositivos suportados

#### 2.1.2 Interfaces de Hardware
- Especificações mínimas
- Periféricos necessários

#### 2.1.3 Interfaces de Software
- APIs externas
- Bibliotecas/Frameworks
- Protocolos de comunicação

### 2.2 Requisitos Funcionais
[PARA CADA FUNCIONALIDADE PRINCIPAL]
#### ID: RF-XXX
**Título**: [Nome descritivo]
**Descrição**: [Detalhada]
**Entrada**: [Dados necessários]
**Processamento**: [Passos do algoritmo]
**Saída**: [Resultado esperado]
**Prioridade**: Alta/Média/Baixa
**Critérios de Aceitação**:
1. [Condição verificável]
2. [Condição verificável]

### 2.3 Requisitos de Usabilidade
- Tempo máximo para tarefas comuns
- Taxa de erro aceitável
- Curva de aprendizado
- Satisfação do usuário (métrica)

### 2.4 Requisitos de Performance
#### RP-001: Tempo de Resposta
- Operações síncronas: < X segundos
- Operações assíncronas: < Y segundos
- Queries de banco: < Z ms

#### RP-002: Throughput
- Transações por segundo: X
- Usuários simultâneos: Y
- Volume de dados: Z GB

### 2.5 Requisitos de Banco de Dados
- Modelo de dados (relacional/NoSQL)
- Volume estimado
- Backup e recuperação
- Retenção de dados

### 2.6 Restrições de Design
- Arquitetura (monolítica/microserviços)
- Padrões de codificação
- Frameworks obrigatórios

### 2.7 Atributos de Qualidade
#### Confiabilidade
- MTBF (Mean Time Between Failures): X horas
- Disponibilidade: 99.X%

#### Segurança
- Autenticação/Autorização
- Criptografia (em trânsito e em repouso)
- Auditoria e logs

#### Manutenibilidade
- Modularidade
- Documentação do código
- Testes automatizados (cobertura > X%)

## 3. VERIFICAÇÃO

### 3.1 Métodos de Verificação
Para cada requisito, especificar:
- **Inspeção**: Revisão de código/documentação
- **Demonstração**: Execução de funcionalidade
- **Teste**: Casos de teste automatizados
- **Análise**: Métricas e benchmarks

### 3.2 Matriz de Rastreabilidade
| ID Requisito | Caso de Uso | Módulo | Teste | Status |
|--------------|-------------|---------|--------|---------|
| RF-001       | UC-001      | Auth    | TC-001 | [ ]     |

## 4. INFORMAÇÕES DE SUPORTE

### 4.1 Glossário
[Termos técnicos e de negócio]

### 4.2 Modelos de Análise
- Diagramas de caso de uso
- Diagramas de sequência
- Modelo de dados

### 4.3 Lista de Pendências
[Decisões ainda não tomadas]

# Instruções de Geração

1. **Análise das Respostas**: Extraia TODAS as informações relevantes
2. **Preenchimento**: Complete TODAS as seções, marcando "A ser definido" quando necessário
3. **Requisitos SMART**: Cada requisito deve ser específico e mensurável
4. **IDs Únicos**: Use padrão consistente (RF-XXX, RNF-XXX, RP-XXX)
5. **Linguagem**: Clara, precisa, sem jargões desnecessários
6. **Formato**: Markdown bem estruturado com hierarquia clara
7. **Verificabilidade**: Todo requisito deve ter critério de aceitação claro
8. **Priorização**: Classifique requisitos por importância
9. **Rastreabilidade**: Conecte requisitos a objetivos de negócio

# Exemplo de Requisito Bem Escrito
❌ RUIM: "O sistema deve ser rápido"
✅ BOM: "RF-001: O sistema deve processar requisições de login em no máximo 2 segundos para 95% dos casos, medido do envio do formulário até o redirecionamento do usuário autenticado"'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Resumo do projeto: {$projectSummary}\n\nQ&A:\n{$qaString}"
                    ]
                ]
            ]);

            if ($response->successful()) {
                $srsDocument = $response->json()['choices'][0]['message']['content'];
                
                // Validar SRS
                $validationResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => '# Papel
Você é um auditor de qualidade de software e especialista em validação de requisitos com certificações em ISO/IEC/IEEE 29148:2018.

# Contexto
Você está realizando uma revisão crítica e minuciosa de um documento SRS para garantir sua qualidade e conformidade com padrões internacionais.

# Objetivo da Validação
Identificar e reportar:
1. Requisitos ambíguos ou mal definidos
2. Falta de mensurabilidade
3. Inconsistências entre seções
4. Requisitos não testáveis
5. Violações do padrão ISO 29148
6. Oportunidades de melhoria

# Critérios de Validação SMART+C

## S - Specific (Específico)
- ❌ VAGO: "O sistema deve ser intuitivo"
- ✅ ESPECÍFICO: "O sistema deve permitir que novos usuários completem o cadastro em menos de 3 minutos sem assistência"

## M - Measurable (Mensurável)
- ❌ NÃO MENSURÁVEL: "Performance adequada"
- ✅ MENSURÁVEL: "Tempo de resposta < 2 segundos para 95% das requisições"

## A - Achievable (Alcançável)
- Avaliar viabilidade técnica
- Considerar recursos disponíveis
- Verificar conflitos tecnológicos

## R - Relevant (Relevante)
- Alinhamento com objetivos de negócio
- Valor agregado ao usuário
- Priorização adequada

## T - Time-bound (Temporal)
- Prazos definidos
- Marcos de entrega
- Critérios de performance temporal

## C - Consistent (Consistente)
- Sem contradições entre requisitos
- Terminologia uniforme
- Referências cruzadas corretas

# Estrutura do Relatório de Validação

## 1. RESUMO EXECUTIVO
- Nota geral de qualidade (A-F)
- Principais problemas encontrados
- Recomendações críticas

## 2. ANÁLISE POR SEÇÃO

### [Nome da Seção]
#### Pontos Fortes:
- ✅ [Aspecto positivo]

#### Problemas Identificados:
- ❌ **[ID-Problema]**: [Descrição]
  - **Impacto**: Alto/Médio/Baixo
  - **Sugestão**: [Melhoria específica]

## 3. ANÁLISE DE REQUISITOS

### Requisitos Funcionais
Para cada requisito problemático:
```
ID: RF-XXX
Problema: [Descrição do problema]
Requisito Atual: "[Texto atual]"
Requisito Sugerido: "[Texto melhorado]"
Justificativa: [Por que a mudança é necessária]
```

### Requisitos Não-Funcionais
[Mesma estrutura]

## 4. MATRIZ DE CONFORMIDADE

| Critério ISO 29148 | Status | Observações |
|-------------------|---------|-------------|
| Completude        | ⚠️/✅/❌ | [Comentário] |
| Consistência      | ⚠️/✅/❌ | [Comentário] |
| Rastreabilidade   | ⚠️/✅/❌ | [Comentário] |
| Testabilidade     | ⚠️/✅/❌ | [Comentário] |
| Clareza           | ⚠️/✅/❌ | [Comentário] |

## 5. RISCOS IDENTIFICADOS

### Risco Técnico
- **RT-001**: [Descrição]
  - Probabilidade: Alta/Média/Baixa
  - Impacto: Alto/Médio/Baixo
  - Mitigação: [Sugestão]

### Risco de Negócio
[Mesma estrutura]

## 6. CHECKLIST DE VALIDAÇÃO

- [ ] Todos os requisitos têm IDs únicos
- [ ] Requisitos são verificáveis
- [ ] Não há contradições
- [ ] Prioridades estão definidas
- [ ] Critérios de aceitação claros
- [ ] Interfaces bem especificadas
- [ ] Requisitos de performance quantificados
- [ ] Requisitos de segurança adequados
- [ ] Glossário completo
- [ ] Rastreabilidade implementada

## 7. MÉTRICAS DE QUALIDADE

- **Requisitos Ambíguos**: X de Y (Z%)
- **Requisitos Não-Testáveis**: X de Y (Z%)
- **Requisitos Sem Prioridade**: X de Y (Z%)
- **Cobertura de Casos de Uso**: Z%
- **Índice de Completude**: Z%

## 8. RECOMENDAÇÕES PRIORITÁRIAS

1. **CRÍTICO**: [Ação necessária imediatamente]
2. **ALTO**: [Ação necessária antes do desenvolvimento]
3. **MÉDIO**: [Melhorias recomendadas]
4. **BAIXO**: [Otimizações opcionais]

# Instruções de Análise

1. **Leia TODO o documento** antes de iniciar a validação
2. **Seja ESPECÍFICO** em suas críticas - cite trechos exatos
3. **Forneça SOLUÇÕES** concretas, não apenas problemas
4. **Priorize** os problemas por impacto
5. **Use EXEMPLOS** para ilustrar melhorias
6. **Mantenha tom PROFISSIONAL** mas acessível
7. **Destaque** tanto pontos positivos quanto negativos
8. **Quantifique** sempre que possível

# Escala de Severidade
- 🔴 **BLOQUEANTE**: Impede o desenvolvimento
- 🟡 **MAIOR**: Causa problemas significativos
- 🟢 **MENOR**: Melhorias recomendadas
- 🔵 **INFORMATIVO**: Sugestões opcionais'
                        ],
                        [
                            'role' => 'user',
                            'content' => "SRS Document: {$srsDocument}"
                        ]
                    ]
                ]);

                $validation = '';
                if ($validationResponse->successful()) {
                    $validation = $validationResponse->json()['choices'][0]['message']['content'];
                }

                // Salvar no histórico
                try {
                    SrsHistory::create([
                        'session_id' => session()->getId(),
                        'version' => 'v1',
                        'answers' => $answers,
                        'srs_document' => $srsDocument,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]);
                } catch (\Exception $e) {
                    // Log error but don't fail the request
                    \Log::error('Failed to save SRS history: ' . $e->getMessage());
                }
                
                Session::put('srs_document', $srsDocument);
                Session::put('srs_validation', $validation);

                return response()->json([
                    'success' => true,
                    'srs' => $srsDocument,
                    'validation' => $validation
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Erro ao gerar o SRS.'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao conectar com a API: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadSRS()
    {
        $srsDocument = Session::get('srs_document', '');
        
        if (empty($srsDocument)) {
            return redirect()->route('agente-srs.index')->with('error', 'Nenhum documento SRS disponível para download.');
        }

        $filename = 'srs_document_' . date('Y-m-d_H-i-s') . '.md';
        
        return response($srsDocument)
            ->header('Content-Type', 'text/markdown')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}