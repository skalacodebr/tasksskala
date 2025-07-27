<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agente de Geração de SRS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8" x-data="srsAgent()">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Agente de Geração de SRS Integrado com OpenAI</h1>

        <!-- Formulário Inicial -->
        <div x-show="!questions.length && !srsDocument" class="bg-white rounded-lg shadow-md p-6 mb-8">

            <div class="mb-6">
                <label for="project_summary" class="block text-sm font-medium text-gray-700 mb-2">
                    Forneça um resumo do projeto:
                </label>
                <textarea 
                    id="project_summary" 
                    x-model="projectSummary"
                    rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border"
                    placeholder="Descreva o projeto que deseja especificar..."
                ></textarea>
                <button 
                    @click="enrichDescription()"
                    :disabled="enriching || !projectSummary"
                    class="mt-2 bg-gray-600 text-white py-1 px-3 rounded-md hover:bg-gray-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors text-sm"
                    type="button"
                >
                    <span x-show="!enriching">✨ Enriquecer Descrição</span>
                    <span x-show="enriching">Enriquecendo...</span>
                </button>
            </div>

            <button 
                @click="generateQuestions()"
                :disabled="loading || !projectSummary"
                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
            >
                <span x-show="!loading">Gerar Perguntas</span>
                <span x-show="loading">Gerando perguntas via OpenAI...</span>
            </button>

            <div x-show="error" class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <p x-text="error"></p>
            </div>
        </div>

        <!-- Perguntas -->
        <div x-show="questions.length > 0 && !srsDocument" x-cloak class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4">Perguntas Geradas para o SRS</h2>
            <p class="text-gray-600 mb-6">Para cada pergunta, escolha uma das três sugestões ou digite a sua própria resposta.</p>

            <div class="space-y-6">
                <template x-for="(question, index) in questions" :key="index">
                    <div>
                        <!-- Seção -->
                        <template x-if="!currentSection || currentSection !== question.section">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3" x-text="question.section"></h3>
                        </template>
                        <div x-init="currentSection = question.section"></div>

                        <!-- Subseção -->
                        <template x-if="question.subsection && (!currentSubsection || currentSubsection !== question.subsection)">
                            <h4 class="text-lg font-medium text-gray-700 mb-2" x-text="question.subsection"></h4>
                        </template>
                        <div x-init="currentSubsection = question.subsection"></div>

                        <!-- Pergunta -->
                        <div class="mb-4">
                            <p class="font-medium text-gray-700 mb-2" x-text="'• ' + question.question"></p>
                            
                            <!-- Sugestões -->
                            <template x-if="question.suggestions && question.suggestions.length > 0">
                                <div class="space-y-2 ml-4">
                                    <p class="text-xs text-gray-500 mb-2">Selecione uma ou mais opções:</p>
                                    <template x-for="(suggestion, sugIndex) in question.suggestions" :key="sugIndex">
                                        <label class="flex items-start">
                                            <input 
                                                type="checkbox" 
                                                :value="suggestion"
                                                @change="toggleAnswer(question.question, suggestion)"
                                                :checked="isAnswerSelected(question.question, suggestion)"
                                                class="mt-1 mr-2"
                                            >
                                            <span x-text="suggestion" class="text-sm text-gray-600"></span>
                                        </label>
                                    </template>
                                    <!-- Campo para adicionar novas opções -->
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <p class="text-xs text-gray-500 mb-2">Adicione outras opções:</p>
                                        <div class="flex gap-2">
                                            <input 
                                                type="text"
                                                x-model="customInput[question.question]"
                                                @keyup.enter="addCustomOption(question.question)"
                                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border text-sm"
                                                placeholder="Digite e pressione Enter para adicionar..."
                                            >
                                            <button 
                                                @click="addCustomOption(question.question)"
                                                type="button"
                                                class="bg-green-600 text-white px-3 py-2 rounded-md hover:bg-green-700 text-sm"
                                            >
                                                + Adicionar
                                            </button>
                                        </div>
                                        <!-- Mostrar opções customizadas adicionadas -->
                                        <div x-show="customOptions[question.question] && customOptions[question.question].length > 0" class="mt-2 space-y-2">
                                            <template x-for="(option, optIndex) in customOptions[question.question]" :key="optIndex">
                                                <label class="flex items-start">
                                                    <input 
                                                        type="checkbox" 
                                                        :value="option"
                                                        @change="toggleAnswer(question.question, option)"
                                                        :checked="isAnswerSelected(question.question, option)"
                                                        class="mt-1 mr-2"
                                                    >
                                                    <span class="text-sm text-gray-600" x-text="option"></span>
                                                    <button 
                                                        @click="removeCustomOption(question.question, optIndex)"
                                                        type="button"
                                                        class="ml-auto text-red-500 hover:text-red-700"
                                                    >
                                                        ×
                                                    </button>
                                                </label>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Input especial para sistemas de referência -->
                            <template x-if="question.question.includes('sistemas ou plataformas') && question.question.includes('referência')">
                                <div class="ml-4 space-y-2">
                                    <div class="flex gap-2">
                                        <input 
                                            type="text"
                                            x-model="referenceInput"
                                            @keyup.enter="addReference(question.question)"
                                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border text-sm"
                                            placeholder="Digite o nome do sistema e pressione Enter..."
                                        >
                                        <button 
                                            @click="addReference(question.question)"
                                            type="button"
                                            class="bg-indigo-600 text-white px-3 py-2 rounded-md hover:bg-indigo-700 text-sm"
                                        >
                                            + Adicionar
                                        </button>
                                    </div>
                                    <div x-show="referencesList[question.question] && referencesList[question.question].length > 0" class="flex flex-wrap gap-2">
                                        <template x-for="(ref, index) in referencesList[question.question]" :key="index">
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                                                <span x-text="ref"></span>
                                                <button 
                                                    @click="removeReference(question.question, index)"
                                                    type="button"
                                                    class="ml-1 hover:text-indigo-600"
                                                >
                                                    ×
                                                </button>
                                            </span>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <!-- Input direto para outras perguntas sem sugestões -->
                            <template x-if="(!question.suggestions || question.suggestions.length === 0) && !(question.question.includes('sistemas ou plataformas') && question.question.includes('referência'))">
                                <textarea 
                                    x-model="answers[question.question]"
                                    rows="3"
                                    class="ml-4 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border text-sm"
                                    :placeholder="getPlaceholder(question.question)"
                                ></textarea>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <button 
                @click="generateSRS()"
                :disabled="loading"
                class="mt-8 w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
            >
                <span x-show="!loading">Gerar SRS</span>
                <span x-show="loading">Gerando SRS via OpenAI...</span>
            </button>
        </div>

        <!-- SRS Gerado -->
        <div x-show="srsDocument" x-cloak>
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-semibold mb-4">SRS Gerado</h2>
                <div class="prose max-w-none" x-html="renderMarkdown(srsDocument)"></div>
            </div>

            <!-- Validação -->
            <div x-show="validation" class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-semibold mb-4">Validação do SRS</h2>
                <div class="prose max-w-none" x-html="renderMarkdown(validation)"></div>
            </div>

            <!-- Botões de ação -->
            <div class="flex gap-4">
                <a 
                    href="{{ route('agente-srs.download') }}"
                    class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors"
                >
                    Baixar SRS como Markdown
                </a>
                <button 
                    @click="reset()"
                    class="bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition-colors"
                >
                    Novo Projeto
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        function srsAgent() {
            return {
                apiKey: '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',
                projectSummary: '',
                questions: [],
                answers: {},
                srsDocument: '',
                validation: '',
                loading: false,
                error: '',
                currentSection: '',
                currentSubsection: '',
                enriching: false,
                selectedAnswers: {},
                customAnswers: {},
                referenceInput: '',
                referencesList: {},
                customInput: {},
                customOptions: {},

                addCustomOption(question) {
                    if (!this.customInput[question] || !this.customInput[question].trim()) return;
                    
                    if (!this.customOptions[question]) {
                        this.customOptions[question] = [];
                    }
                    
                    const newOption = this.customInput[question].trim();
                    if (!this.customOptions[question].includes(newOption)) {
                        this.customOptions[question].push(newOption);
                        this.customInput[question] = '';
                    }
                },

                removeCustomOption(question, index) {
                    if (this.customOptions[question]) {
                        const removedOption = this.customOptions[question][index];
                        this.customOptions[question].splice(index, 1);
                        
                        // Remove da seleção se estava selecionado
                        if (this.selectedAnswers[question]) {
                            const answerIndex = this.selectedAnswers[question].indexOf(removedOption);
                            if (answerIndex > -1) {
                                this.selectedAnswers[question].splice(answerIndex, 1);
                                this.updateAnswer(question);
                            }
                        }
                    }
                },

                addReference(question) {
                    if (!this.referenceInput.trim()) return;
                    
                    if (!this.referencesList[question]) {
                        this.referencesList[question] = [];
                    }
                    
                    this.referencesList[question].push(this.referenceInput.trim());
                    this.referenceInput = '';
                    this.updateReferencesAnswer(question);
                },

                removeReference(question, index) {
                    if (this.referencesList[question]) {
                        this.referencesList[question].splice(index, 1);
                        this.updateReferencesAnswer(question);
                    }
                },

                updateReferencesAnswer(question) {
                    if (this.referencesList[question] && this.referencesList[question].length > 0) {
                        this.answers[question] = this.referencesList[question].join('; ');
                    } else {
                        this.answers[question] = '';
                    }
                },

                toggleAnswer(question, answer) {
                    if (!this.selectedAnswers[question]) {
                        this.selectedAnswers[question] = [];
                    }
                    
                    const index = this.selectedAnswers[question].indexOf(answer);
                    if (index > -1) {
                        this.selectedAnswers[question].splice(index, 1);
                    } else {
                        this.selectedAnswers[question].push(answer);
                    }
                    
                    this.updateAnswer(question);
                },

                toggleCustomAnswer(question) {
                    if (!this.customAnswers[question]) {
                        this.customAnswers[question] = '';
                    }
                    
                    if (this.hasCustomAnswer(question)) {
                        delete this.customAnswers[question];
                    } else {
                        this.customAnswers[question] = '';
                    }
                    
                    this.updateAnswer(question);
                },

                hasCustomAnswer(question) {
                    return this.customAnswers.hasOwnProperty(question);
                },

                isAnswerSelected(question, answer) {
                    return this.selectedAnswers[question] && this.selectedAnswers[question].includes(answer);
                },

                updateAnswer(question) {
                    const selected = this.selectedAnswers[question] || [];
                    const custom = this.customAnswers[question];
                    
                    let finalAnswer = [];
                    
                    if (selected.length > 0) {
                        finalAnswer = finalAnswer.concat(selected);
                    }
                    
                    if (custom && custom.trim()) {
                        finalAnswer.push(custom.trim());
                    }
                    
                    this.answers[question] = finalAnswer.length > 0 ? finalAnswer.join('; ') : '';
                },

                async enrichDescription() {
                    this.enriching = true;
                    this.error = '';

                    try {
                        const response = await fetch('{{ route("agente-srs.enriquecer-descricao") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                api_key: this.apiKey,
                                project_summary: this.projectSummary
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.projectSummary = data.enrichedDescription;
                        } else {
                            this.error = data.error || 'Erro ao enriquecer descrição';
                        }
                    } catch (error) {
                        this.error = 'Erro de conexão: ' + error.message;
                    } finally {
                        this.enriching = false;
                    }
                },

                async generateQuestions() {
                    this.loading = true;
                    this.error = '';

                    try {
                        const response = await fetch('{{ route("agente-srs.gerar-perguntas") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                api_key: this.apiKey,
                                project_summary: this.projectSummary
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.questions = data.questions;
                            // Inicializar respostas vazias
                            this.questions.forEach(q => {
                                // Pré-preencher público-alvo com sugestão
                                if (q.question.includes('público-alvo')) {
                                    this.answers[q.question] = 'Pequenas e médias empresas que buscam soluções digitais para otimizar seus processos, com foco em gestores e colaboradores que precisam de ferramentas intuitivas e de fácil adoção';
                                } else {
                                    this.answers[q.question] = '';
                                }
                                this.selectedAnswers[q.question] = [];
                                this.customAnswers[q.question] = '';
                                this.customInput[q.question] = '';
                                this.customOptions[q.question] = [];
                            });
                        } else {
                            this.error = data.error || 'Erro ao gerar perguntas';
                        }
                    } catch (error) {
                        this.error = 'Erro de conexão: ' + error.message;
                    } finally {
                        this.loading = false;
                    }
                },

                async generateSRS() {
                    this.loading = true;
                    this.error = '';

                    try {
                        const response = await fetch('{{ route("agente-srs.gerar-srs") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                answers: this.answers
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.srsDocument = data.srs;
                            this.validation = data.validation;
                        } else {
                            this.error = data.error || 'Erro ao gerar SRS';
                        }
                    } catch (error) {
                        this.error = 'Erro de conexão: ' + error.message;
                    } finally {
                        this.loading = false;
                    }
                },

                getPlaceholder(question) {
                    if (question.includes('público-alvo')) {
                        return 'Ex: Pequenas e médias empresas do setor de varejo que buscam automatizar seus processos de vendas e gestão de estoque, com foco em empreendedores com pouco conhecimento técnico...';
                    }
                    return 'Digite sua resposta...';
                },

                renderMarkdown(text) {
                    return marked.parse(text);
                },

                reset() {
                    this.questions = [];
                    this.answers = {};
                    this.selectedAnswers = {};
                    this.customAnswers = {};
                    this.referencesList = {};
                    this.referenceInput = '';
                    this.customInput = {};
                    this.customOptions = {};
                    this.srsDocument = '';
                    this.validation = '';
                    this.currentSection = '';
                    this.currentSubsection = '';
                }
            }
        }
    </script>
</body>
</html>