<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agente SRS v2 - Formulário ISO/IEC/IEEE 29148:2018</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        .category-section {
            @apply bg-white rounded-lg shadow-md p-6 mb-6;
        }
        .question-block {
            @apply mb-6 pb-6 border-b border-gray-200 last:border-b-0 last:pb-0;
        }
        .required-field::after {
            content: " *";
            @apply text-red-500;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8" x-data="srsForm()">
        <div class="max-w-5xl mx-auto">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Agente de Geração de SRS - ISO/IEC/IEEE 29148:2018</h1>
                    <p class="text-gray-600">Formulário estruturado baseado no padrão internacional para especificação de requisitos de software</p>
                    @if(isset($projeto_nome))
                    <p class="text-sm text-blue-600 mt-2">
                        <strong>Projeto:</strong> {{ $projeto_nome }}
                    </p>
                    @endif
                </div>
                <a href="{{ route('agente-srs2.historico') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Ver Histórico
                </a>
            </div>

            <!-- Botão de Ajuda para Conduzir Entrevista -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-blue-900 mb-1">Guia para Conduzir a Entrevista</h3>
                        <p class="text-sm text-blue-700">Dicas e técnicas para elicitar requisitos efetivamente com o cliente</p>
                    </div>
                    <button 
                        @click="showInterviewGuide = true"
                        class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        Abrir Guia de Entrevista
                    </button>
                </div>
            </div>

            <!-- Progresso -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-8">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Progresso do Formulário</span>
                    <span class="text-sm text-gray-500" x-text="progress + '%'"></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                </div>
            </div>

            <!-- Navegação por Categorias -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-8 sticky top-4 z-10">
                <div class="flex flex-wrap gap-2">
                    <template x-for="(category, key) in categories" :key="key">
                        <button 
                            @click="scrollToCategory(key)"
                            class="px-3 py-1 rounded-full text-sm transition-colors"
                            :class="currentCategory === key ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                            x-text="category.shortTitle || category.title"
                        ></button>
                    </template>
                </div>
            </div>

            <!-- Formulário -->
            <form @submit.prevent="generateSRS()">
                @foreach($questionCategories as $categoryKey => $category)
                <div class="category-section" :id="'category-{{ $categoryKey }}'" x-intersect="currentCategory = '{{ $categoryKey }}'">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ $category['title'] }}</h2>
                    <p class="text-sm text-gray-600 mb-6">{{ $category['description'] }}</p>

                    @foreach($category['questions'] as $question)
                    <div class="question-block">
                        <div class="flex justify-between items-start mb-2">
                            <label for="{{ $categoryKey }}_{{ $question['id'] }}" class="block text-sm font-medium text-gray-700">
                                <span @if($question['required']) class="required-field" @endif>
                                    {{ $question['question'] }}
                                </span>
                            </label>
                            @if($question['type'] !== 'dynamic_functions')
                            <button 
                                type="button"
                                @click="suggestAnswer('{{ $categoryKey }}', '{{ $question['id'] }}')"
                                :disabled="loadingSuggestion['{{ $categoryKey }}_{{ $question['id'] }}']"
                                class="ml-4 px-3 py-1 bg-blue-500 text-white text-xs rounded-md hover:bg-blue-600 disabled:bg-gray-400 transition-colors flex items-center gap-1"
                                title="Sugerir resposta baseada no contexto"
                            >
                                <svg x-show="!loadingSuggestion['{{ $categoryKey }}_{{ $question['id'] }}']" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                <svg x-show="loadingSuggestion['{{ $categoryKey }}_{{ $question['id'] }}']" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span x-text="loadingSuggestion['{{ $categoryKey }}_{{ $question['id'] }}'] ? 'Gerando...' : 'Sugerir'"></span>
                            </button>
                            @endif
                        </div>
                        
                        @if($question['type'] === 'textarea')
                        <div class="relative">
                            <div class="relative">
                                <textarea 
                                    id="{{ $categoryKey }}_{{ $question['id'] }}"
                                    name="answers[{{ $categoryKey }}][{{ $question['id'] }}]"
                                    x-model="answers['{{ $categoryKey }}']['{{ $question['id'] }}']"
                                    @input="updateProgress(); updateContext()"
                                    rows="4"
                                    @if($question['required']) required @endif
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3 pr-12 border"
                                    placeholder="Digite sua resposta aqui..."
                                ></textarea>
                                
                                <!-- Botão de microfone -->
                                <button 
                                    type="button"
                                    @click="toggleSpeechRecognition('{{ $categoryKey }}', '{{ $question['id'] }}')"
                                    :class="isRecording['{{ $categoryKey }}_{{ $question['id'] }}'] ? 'bg-red-500 hover:bg-red-600 animate-pulse' : 'bg-gray-500 hover:bg-gray-600'"
                                    class="absolute bottom-3 right-3 p-2 text-white rounded-full transition-colors"
                                    :title="isRecording['{{ $categoryKey }}_{{ $question['id'] }}'] ? 'Parar gravação' : 'Iniciar gravação de voz'"
                                >
                                    <svg x-show="!isRecording['{{ $categoryKey }}_{{ $question['id'] }}']" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                    </svg>
                                    <svg x-show="isRecording['{{ $categoryKey }}_{{ $question['id'] }}']" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Indicador de contexto sendo usado -->
                            <div x-show="hasContext() && answers['{{ $categoryKey }}']['{{ $question['id'] }}'].length > 0" 
                                 class="absolute top-2 right-2 text-xs text-green-600 bg-green-50 px-2 py-1 rounded">
                                ✓ Contexto atualizado
                            </div>
                        </div>
                        @elseif($question['type'] === 'dynamic_functions')
                        <div x-data="dynamicFunctions()">
                            <!-- Botão de sugestão para funcionalidades -->
                            <div class="mb-4 flex justify-end">
                                <button 
                                    type="button"
                                    @click="suggestFunctionalities()"
                                    :disabled="loadingSuggestion"
                                    class="px-3 py-1 bg-blue-500 text-white text-xs rounded-md hover:bg-blue-600 disabled:bg-gray-400 transition-colors flex items-center gap-1"
                                    title="Sugerir funcionalidades baseadas no contexto"
                                >
                                    <svg x-show="!loadingSuggestion" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                    <svg x-show="loadingSuggestion" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span x-text="loadingSuggestion ? 'Gerando...' : 'Sugerir Funcionalidades'"></span>
                                </button>
                            </div>
                            
                            <!-- Lista de funcionalidades -->
                            <div class="space-y-4 mb-4">
                                <template x-for="(func, index) in functionalities" :key="index">
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                        <div class="flex justify-between items-start mb-3">
                                            <h4 class="text-sm font-semibold text-gray-700" x-text="'Funcionalidade ' + (index + 1)"></h4>
                                            <button 
                                                type="button"
                                                @click="removeFunctionality(index)"
                                                class="text-red-500 hover:text-red-700 text-sm"
                                            >
                                                Remover
                                            </button>
                                        </div>
                                        
                                        <!-- Nome da funcionalidade -->
                                        <input 
                                            type="text"
                                            x-model="func.name"
                                            @input="updateFunctionalitiesData()"
                                            placeholder="Nome da funcionalidade (ex: Login de usuários)"
                                            class="w-full mb-3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border"
                                            required
                                        />
                                        
                                        <!-- Fluxo de trabalho -->
                                        <div class="mb-3">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Descreva o fluxo de trabalho: Como o usuário inicia? Quais inputs/outputs?
                                            </label>
                                            <div class="relative">
                                                <textarea 
                                                    x-model="func.workflow"
                                                    @input="updateFunctionalitiesData()"
                                                    rows="3"
                                                    placeholder="Ex: Usuário clica em 'Login', insere email/senha (input), sistema valida e redireciona ao dashboard (output)"
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 pr-10 border text-sm"
                                                ></textarea>
                                                <button 
                                                    type="button"
                                                    @click="toggleFuncSpeech('workflow', index)"
                                                    :class="funcRecording[`workflow_${index}`] ? 'bg-red-500 hover:bg-red-600 animate-pulse' : 'bg-gray-500 hover:bg-gray-600'"
                                                    class="absolute bottom-2 right-2 p-1.5 text-white rounded-full transition-colors text-xs"
                                                    :title="funcRecording[`workflow_${index}`] ? 'Parar gravação' : 'Iniciar gravação de voz'"
                                                >
                                                    <svg x-show="!funcRecording[`workflow_${index}`]" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                                    </svg>
                                                    <svg x-show="funcRecording[`workflow_${index}`]" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Sequências de operações (opcional) -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Sequências específicas de operações (opcional)
                                            </label>
                                            <div class="relative">
                                                <textarea 
                                                    x-model="func.sequences"
                                                    @input="updateFunctionalitiesData()"
                                                    rows="2"
                                                    placeholder="Ex: Validar formato do email > Verificar senha > Criar sessão > Log de acesso"
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 pr-10 border text-sm"
                                                ></textarea>
                                                <button 
                                                    type="button"
                                                    @click="toggleFuncSpeech('sequences', index)"
                                                    :class="funcRecording[`sequences_${index}`] ? 'bg-red-500 hover:bg-red-600 animate-pulse' : 'bg-gray-500 hover:bg-gray-600'"
                                                    class="absolute bottom-2 right-2 p-1.5 text-white rounded-full transition-colors text-xs"
                                                    :title="funcRecording[`sequences_${index}`] ? 'Parar gravação' : 'Iniciar gravação de voz'"
                                                >
                                                    <svg x-show="!funcRecording[`sequences_${index}`]" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                                    </svg>
                                                    <svg x-show="funcRecording[`sequences_${index}`]" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            
                            <!-- Botão para adicionar nova funcionalidade -->
                            <button 
                                type="button"
                                @click="addFunctionality()"
                                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Adicionar Funcionalidade
                            </button>
                            
                            <!-- Campo oculto para armazenar os dados -->
                            <input type="hidden" 
                                   name="answers[{{ $categoryKey }}][{{ $question['id'] }}]"
                                   x-model="functionalitiesJson">
                        </div>
                        @endif

                        @if(isset($question['example']))
                        <div class="mt-2 p-3 bg-gray-50 rounded-md border border-gray-200">
                            <p class="text-xs font-medium text-gray-600 mb-1">Exemplo de resposta:</p>
                            <p class="text-xs text-gray-500 italic">{{ $question['example'] }}</p>
                        </div>
                        @endif

                        @if($question['required'])
                        <p class="text-xs text-red-500 mt-1">* Campo obrigatório</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endforeach

                <!-- Botões de Ação -->
                <div class="bg-white rounded-lg shadow-md p-6 sticky bottom-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">
                                <span x-text="answeredQuestions"></span> de <span x-text="totalQuestions"></span> perguntas respondidas
                            </p>
                        </div>
                        <div class="flex gap-4">
                            <button 
                                type="button"
                                @click="saveDraft()"
                                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
                            >
                                Salvar Rascunho
                            </button>
                            <button 
                                type="submit"
                                :disabled="loading || progress < 100"
                                class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
                            >
                                <span x-show="!loading">Gerar SRS</span>
                                <span x-show="loading">Gerando...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Modal de SRS Gerado -->
            <div x-show="showSRS" x-cloak class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-2xl font-semibold">SRS Gerado</h3>
                        <button @click="showSRS = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="max-h-96 overflow-y-auto mb-4 p-4 bg-gray-50 rounded">
                        <div class="prose max-w-none" x-html="renderMarkdown(srsDocument)"></div>
                    </div>

                    <div class="flex justify-end gap-4">
                        <button 
                            @click="copySRS()"
                            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
                        >
                            Copiar para Clipboard
                        </button>
                        <a 
                            href="{{ route('agente-srs2.download') }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                        >
                            Baixar Markdown
                        </a>
                        <button 
                            @click="showSRS = false"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors"
                        >
                            Fechar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal de Preview da Sugestão -->
            <div x-show="showSuggestionModal" x-cloak class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Sugestão de Resposta</h3>
                        <p class="text-sm text-gray-600 mb-4">A IA gerou a seguinte sugestão baseada no contexto do projeto:</p>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <p class="text-gray-800 whitespace-pre-wrap" x-text="currentSuggestion"></p>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                            <p class="text-sm text-yellow-800">
                                <strong>Nota:</strong> Esta sugestão foi gerada para ser clara, específica e mensurável. 
                                Você pode editá-la após aplicar.
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button 
                            @click="showSuggestionModal = false"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors"
                        >
                            Cancelar
                        </button>
                        <button 
                            @click="applySuggestion()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                        >
                            Aplicar Sugestão
                        </button>
                    </div>
                </div>
            </div>

            <!-- Toast de Notificação -->
            <div x-show="showToast" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform translate-y-4"
                 class="fixed bottom-8 right-8 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                <p x-text="toastMessage"></p>
            </div>

            <!-- Modal de Guia de Entrevista -->
            <div x-show="showInterviewGuide" x-cloak class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-2xl font-semibold text-gray-800">Guia para Conduzir Entrevista de Requisitos</h3>
                        <button @click="showInterviewGuide = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="max-h-[70vh] overflow-y-auto">
                        <div class="space-y-6">
                            <!-- Preparação -->
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    1. Preparação Antes da Reunião
                                </h4>
                                <ul class="space-y-2 text-sm text-gray-700">
                                    <li class="flex items-start">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span><strong>Pesquise sobre o cliente:</strong> Entenda o negócio, setor de atuação e desafios comuns</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span><strong>Prepare o ambiente:</strong> Teste o microfone para gravação de voz, tenha papel para anotações</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span><strong>Revise as perguntas:</strong> Familiarize-se com todas as categorias do formulário</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span><strong>Defina expectativas:</strong> Informe que a reunião durará cerca de 1-2 horas</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Início da Entrevista -->
                            <div class="bg-blue-50 p-6 rounded-lg">
                                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    2. Início da Entrevista
                                </h4>
                                <div class="space-y-3 text-sm text-gray-700">
                                    <div class="bg-white p-4 rounded border border-blue-200">
                                        <p class="font-medium mb-2">Script de Abertura Sugerido:</p>
                                        <p class="italic">"Olá [Nome], obrigado por dedicar seu tempo. Hoje vamos conversar sobre o projeto [Nome do Projeto]. 
                                        Meu objetivo é entender completamente suas necessidades para criar uma especificação detalhada que guiará o desenvolvimento. 
                                        Vou fazer várias perguntas organizadas em categorias. Sinta-se à vontade para dar exemplos e explicar com detalhes."</p>
                                    </div>
                                    <ul class="space-y-2">
                                        <li class="flex items-start">
                                            <span class="text-green-500 mr-2">✓</span>
                                            <span>Explique que você pode usar gravação de voz para capturar as respostas</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="text-green-500 mr-2">✓</span>
                                            <span>Mostre os exemplos disponíveis em cada campo como referência</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Técnicas de Elicitação -->
                            <div class="bg-purple-50 p-6 rounded-lg">
                                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    3. Técnicas de Elicitação Eficazes
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div class="bg-white p-4 rounded border border-purple-200">
                                        <h5 class="font-semibold mb-2 text-purple-700">Perguntas Abertas</h5>
                                        <p class="text-gray-600 mb-2">Use para explorar ideias:</p>
                                        <ul class="space-y-1 text-gray-700">
                                            <li>• "Como você imagina..."</li>
                                            <li>• "Descreva um dia típico..."</li>
                                            <li>• "Quais são os principais desafios..."</li>
                                        </ul>
                                    </div>
                                    <div class="bg-white p-4 rounded border border-purple-200">
                                        <h5 class="font-semibold mb-2 text-purple-700">Perguntas de Sondagem</h5>
                                        <p class="text-gray-600 mb-2">Use para aprofundar:</p>
                                        <ul class="space-y-1 text-gray-700">
                                            <li>• "Pode dar um exemplo?"</li>
                                            <li>• "O que acontece quando...?"</li>
                                            <li>• "Por que isso é importante?"</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="mt-4 bg-white p-4 rounded border border-purple-200">
                                    <h5 class="font-semibold mb-2 text-purple-700">Técnica dos 5 Porquês</h5>
                                    <p class="text-gray-700">Quando o cliente mencionar um problema, pergunte "por quê?" sucessivamente para chegar à raiz:</p>
                                    <p class="text-sm text-gray-600 mt-1">Exemplo: "Sistema lento" → Por quê? → "Muitos dados" → Por quê? → "Sem limpeza automática"...</p>
                                </div>
                            </div>

                            <!-- Navegação por Seções -->
                            <div class="bg-yellow-50 p-6 rounded-lg">
                                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                    4. Navegando pelas Seções do Formulário
                                </h4>
                                <div class="space-y-3 text-sm text-gray-700">
                                    <div class="bg-white p-3 rounded border border-yellow-200">
                                        <p class="font-semibold text-yellow-700">Seção 1 - Propósito e Escopo:</p>
                                        <p>Comece aqui para estabelecer o contexto. Use o botão "Sugerir" após as primeiras respostas.</p>
                                    </div>
                                    <div class="bg-white p-3 rounded border border-yellow-200">
                                        <p class="font-semibold text-yellow-700">Seção 4 - Requisitos Funcionais:</p>
                                        <p>Use "Adicionar Funcionalidade" para cada feature. O botão de microfone facilita descrições longas.</p>
                                    </div>
                                    <div class="bg-white p-3 rounded border border-yellow-200">
                                        <p class="font-semibold text-yellow-700">Dica:</p>
                                        <p>Não precisa seguir a ordem exata. Se o cliente mencionar algo de outra seção, anote e volte depois.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Situações Comuns -->
                            <div class="bg-red-50 p-6 rounded-lg">
                                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    5. Como Lidar com Situações Comuns
                                </h4>
                                <div class="space-y-3 text-sm">
                                    <div class="bg-white p-4 rounded border border-red-200">
                                        <p class="font-semibold text-red-700 mb-1">Cliente muito técnico:</p>
                                        <p class="text-gray-700">Peça para explicar em termos de negócio: "Como isso beneficia o usuário final?"</p>
                                    </div>
                                    <div class="bg-white p-4 rounded border border-red-200">
                                        <p class="font-semibold text-red-700 mb-1">Cliente vago ou indeciso:</p>
                                        <p class="text-gray-700">Use os exemplos do formulário e pergunte: "Seria algo similar a isso?"</p>
                                    </div>
                                    <div class="bg-white p-4 rounded border border-red-200">
                                        <p class="font-semibold text-red-700 mb-1">Cliente quer "tudo":</p>
                                        <p class="text-gray-700">Foque em priorização: "Se pudéssemos entregar apenas 3 funcionalidades primeiro, quais seriam?"</p>
                                    </div>
                                    <div class="bg-white p-4 rounded border border-red-200">
                                        <p class="font-semibold text-red-700 mb-1">Requisitos conflitantes:</p>
                                        <p class="text-gray-700">Documente ambos e pergunte: "Como você vê esses dois requisitos funcionando juntos?"</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Finalização -->
                            <div class="bg-green-50 p-6 rounded-lg">
                                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    6. Finalizando a Entrevista
                                </h4>
                                <ul class="space-y-2 text-sm text-gray-700">
                                    <li class="flex items-start">
                                        <span class="text-green-500 mr-2">✓</span>
                                        <span><strong>Revise rapidamente:</strong> "Deixe-me confirmar os pontos principais..."</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-green-500 mr-2">✓</span>
                                        <span><strong>Salve o rascunho:</strong> Use o botão "Salvar Rascunho" regularmente</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-green-500 mr-2">✓</span>
                                        <span><strong>Próximos passos:</strong> Informe que você gerará o SRS e enviará para revisão</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-green-500 mr-2">✓</span>
                                        <span><strong>Contato futuro:</strong> "Posso entrar em contato se tiver dúvidas?"</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Dicas Extras -->
                            <div class="bg-indigo-50 p-6 rounded-lg">
                                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Dicas Extras para Sucesso
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700">
                                    <div class="flex items-start">
                                        <span class="text-indigo-500 mr-2">💡</span>
                                        <span>Use linguagem simples, evite jargões técnicos</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="text-indigo-500 mr-2">💡</span>
                                        <span>Mantenha contato visual, mostre que está ouvindo</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="text-indigo-500 mr-2">💡</span>
                                        <span>Faça pausas para o cliente processar e pensar</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="text-indigo-500 mr-2">💡</span>
                                        <span>Confirme seu entendimento parafraseando</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="text-indigo-500 mr-2">💡</span>
                                        <span>Use desenhos ou diagramas quando necessário</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="text-indigo-500 mr-2">💡</span>
                                        <span>Grave a reunião (com permissão) para referência</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button 
                            @click="showInterviewGuide = false"
                            class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
                        >
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        function dynamicFunctions() {
            return {
                functionalities: [],
                functionalitiesJson: '',
                loadingSuggestion: false,
                funcRecording: {},
                funcSpeechRecognition: null,
                
                init() {
                    // Tentar carregar dados salvos se existirem
                    const parentElement = this.$el.closest('[x-data]');
                    const parent = Alpine.$data(parentElement);
                    
                    if (parent && parent.answers && parent.answers['requisitos_funcionais']) {
                        const savedData = parent.answers['requisitos_funcionais']['principais_funcoes'];
                        if (savedData) {
                            try {
                                this.functionalities = JSON.parse(savedData);
                            } catch (e) {
                                this.functionalities = [];
                            }
                        }
                    }
                    
                    // Se não há funcionalidades, adicionar uma vazia
                    if (this.functionalities.length === 0) {
                        this.addFunctionality();
                    }
                },
                
                addFunctionality() {
                    this.functionalities.push({
                        name: '',
                        workflow: '',
                        sequences: ''
                    });
                    this.updateFunctionalitiesData();
                },
                
                removeFunctionality(index) {
                    if (this.functionalities.length > 1) {
                        this.functionalities.splice(index, 1);
                        this.updateFunctionalitiesData();
                    } else {
                        alert('Você deve ter pelo menos uma funcionalidade.');
                    }
                },
                
                updateFunctionalitiesData() {
                    this.functionalitiesJson = JSON.stringify(this.functionalities);
                    // Atualizar o modelo do componente pai
                    const parentElement = this.$el.closest('[x-data]');
                    const parent = Alpine.$data(parentElement);
                    
                    if (parent && parent.answers && parent.answers['requisitos_funcionais']) {
                        parent.answers['requisitos_funcionais']['principais_funcoes'] = this.functionalitiesJson;
                        if (parent.updateProgress) parent.updateProgress();
                        if (parent.updateContext) parent.updateContext();
                    }
                },
                
                async suggestFunctionalities() {
                    // Acessar o componente pai através do elemento DOM
                    const parentElement = this.$el.closest('[x-data]');
                    const parent = Alpine.$data(parentElement);
                    
                    // Verificar se há contexto suficiente
                    if (!parent || !parent.hasContext || !parent.hasContext()) {
                        alert('Por favor, responda pelo menos uma pergunta anterior para gerar sugestões contextuais.');
                        return;
                    }
                    
                    this.loadingSuggestion = true;
                    
                    try {
                        const response = await fetch('/teste_agente2/sugerir-resposta', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                current_category: 'requisitos_funcionais',
                                current_question: 'principais_funcoes',
                                context: parent.contextData,
                                is_dynamic_functions: true
                            })
                        });

                        const data = await response.json();

                        if (data.success && data.functionalities) {
                            // Limpar funcionalidades existentes e adicionar as sugeridas
                            this.functionalities = data.functionalities;
                            this.updateFunctionalitiesData();
                            if (parent.showNotification) {
                                parent.showNotification('Funcionalidades sugeridas adicionadas com sucesso!');
                            }
                        } else {
                            alert(data.error || 'Erro ao gerar sugestão');
                        }
                    } catch (error) {
                        alert('Erro de conexão: ' + error.message);
                    } finally {
                        this.loadingSuggestion = false;
                    }
                },
                
                toggleFuncSpeech(field, index) {
                    const key = `${field}_${index}`;
                    
                    if (this.funcRecording[key]) {
                        this.stopFuncSpeech(key);
                    } else {
                        this.startFuncSpeech(field, index);
                    }
                },
                
                startFuncSpeech(field, index) {
                    const key = `${field}_${index}`;
                    
                    // Verificar suporte
                    if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
                        alert('Seu navegador não suporta reconhecimento de voz. Por favor, use Chrome, Edge ou Safari.');
                        return;
                    }
                    
                    // Criar instância do reconhecimento
                    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                    this.funcSpeechRecognition = new SpeechRecognition();
                    
                    // Configurar reconhecimento
                    this.funcSpeechRecognition.lang = 'pt-BR';
                    this.funcSpeechRecognition.continuous = true;
                    this.funcSpeechRecognition.interimResults = true;
                    this.funcSpeechRecognition.maxAlternatives = 1;
                    
                    // Marcar como gravando
                    this.funcRecording[key] = true;
                    
                    // Armazenar texto temporário
                    let finalTranscript = this.functionalities[index][field] || '';
                    let interimTranscript = '';
                    
                    // Evento de resultado
                    this.funcSpeechRecognition.onresult = (event) => {
                        interimTranscript = '';
                        
                        for (let i = event.resultIndex; i < event.results.length; i++) {
                            const transcript = event.results[i][0].transcript;
                            
                            if (event.results[i].isFinal) {
                                finalTranscript += transcript + ' ';
                            } else {
                                interimTranscript += transcript;
                            }
                        }
                        
                        // Atualizar o campo com o texto reconhecido
                        this.functionalities[index][field] = (finalTranscript + interimTranscript).trim();
                        this.updateFunctionalitiesData();
                    };
                    
                    // Evento de erro
                    this.funcSpeechRecognition.onerror = (event) => {
                        console.error('Erro no reconhecimento de voz:', event.error);
                        this.funcRecording[key] = false;
                        alert('Erro no reconhecimento de voz: ' + event.error);
                    };
                    
                    // Evento de fim
                    this.funcSpeechRecognition.onend = () => {
                        this.funcRecording[key] = false;
                    };
                    
                    // Iniciar reconhecimento
                    try {
                        this.funcSpeechRecognition.start();
                    } catch (e) {
                        console.error('Erro ao iniciar reconhecimento:', e);
                        this.funcRecording[key] = false;
                    }
                },
                
                stopFuncSpeech(key) {
                    if (this.funcSpeechRecognition) {
                        this.funcSpeechRecognition.stop();
                        this.funcRecording[key] = false;
                    }
                }
            }
        }
        
        function srsForm() {
            return {
                speechRecognition: null,
                isRecording: {},
                categories: {
                    @foreach($questionCategories as $key => $category)
                    '{{ $key }}': {
                        title: '{{ $category['title'] }}',
                        shortTitle: '{{ explode('.', $category['title'])[0] }}. {{ explode(' ', explode('.', $category['title'])[1])[1] ?? '' }}'
                    },
                    @endforeach
                },
                answers: {
                    @foreach($questionCategories as $categoryKey => $category)
                    '{{ $categoryKey }}': {
                        @foreach($category['questions'] as $question)
                        '{{ $question['id'] }}': '',
                        @endforeach
                    },
                    @endforeach
                },
                currentCategory: 'proposito_escopo',
                progress: 0,
                totalQuestions: 0,
                answeredQuestions: 0,
                loading: false,
                showSRS: false,
                srsDocument: '',
                showToast: false,
                toastMessage: '',
                loadingSuggestion: {},
                contextData: {},
                showSuggestionModal: false,
                currentSuggestion: '',
                currentSuggestionTarget: null,
                showInterviewGuide: false,

                init() {
                    // Contar total de perguntas obrigatórias
                    @php
                        $totalRequired = 0;
                        foreach($questionCategories as $category) {
                            foreach($category['questions'] as $question) {
                                if($question['required']) $totalRequired++;
                            }
                        }
                    @endphp
                    this.totalQuestions = {{ $totalRequired }};
                    
                    // Verificar suporte para Web Speech API
                    if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
                        console.warn('Web Speech API não suportada neste navegador');
                    }
                    
                    // Carregar rascunho se existir
                    this.loadDraft();
                    this.updateProgress();
                    this.updateContext();
                },

                updateProgress() {
                    let answered = 0;
                    @foreach($questionCategories as $categoryKey => $category)
                        @foreach($category['questions'] as $question)
                            @if($question['required'])
                            if ('{{ $question['type'] }}' === 'dynamic_functions') {
                                // Para campos dinâmicos, verificar se há pelo menos uma funcionalidade válida
                                try {
                                    const funcs = JSON.parse(this.answers['{{ $categoryKey }}']['{{ $question['id'] }}'] || '[]');
                                    if (funcs.length > 0 && funcs.some(f => f.name && f.name.trim())) {
                                        answered++;
                                    }
                                } catch (e) {
                                    // Ignorar erro de parse
                                }
                            } else if (this.answers['{{ $categoryKey }}']['{{ $question['id'] }}'].trim()) {
                                answered++;
                            }
                            @endif
                        @endforeach
                    @endforeach
                    
                    this.answeredQuestions = answered;
                    this.progress = Math.round((answered / this.totalQuestions) * 100);
                },

                scrollToCategory(categoryKey) {
                    const element = document.getElementById('category-' + categoryKey);
                    if (element) {
                        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                },

                saveDraft() {
                    localStorage.setItem('srs_draft_v2', JSON.stringify(this.answers));
                    this.showNotification('Rascunho salvo com sucesso!');
                },

                loadDraft() {
                    const draft = localStorage.getItem('srs_draft_v2');
                    if (draft) {
                        try {
                            const parsed = JSON.parse(draft);
                            Object.assign(this.answers, parsed);
                            this.updateContext(); // Atualizar contexto após carregar rascunho
                        } catch (e) {
                            console.error('Erro ao carregar rascunho:', e);
                        }
                    }
                },

                async generateSRS() {
                    if (this.progress < 100) {
                        alert('Por favor, responda todas as perguntas obrigatórias antes de gerar o SRS.');
                        return;
                    }

                    this.loading = true;

                    try {
                        const response = await fetch('{{ route("agente-srs2.gerar-srs") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                answers: this.answers,
                                projeto_id: {{ $projeto_id ?? 'null' }}
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.srsDocument = data.srs;
                            this.showSRS = true;
                            localStorage.removeItem('srs_draft_v2'); // Limpar rascunho após sucesso
                            
                            // Se veio de reuniões, vincular automaticamente
                            @if(isset($projeto_id))
                            this.vincularRequisitoProjeto();
                            @endif
                        } else {
                            alert(data.error || 'Erro ao gerar SRS');
                        }
                    } catch (error) {
                        alert('Erro de conexão: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                },
                
                async vincularRequisitoProjeto() {
                    try {
                        const response = await fetch('{{ route("reunioes.vincular-requisito") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                projeto_id: {{ $projeto_id ?? 'null' }},
                                requisito_id: {{ session('ultimo_requisito_id') ?? 'null' }}
                            })
                        });
                        
                        const data = await response.json();
                        if (data.success) {
                            this.showToast('Requisito vinculado ao projeto com sucesso!');
                        }
                    } catch (error) {
                        console.error('Erro ao vincular requisito:', error);
                    }
                },

                renderMarkdown(text) {
                    return marked.parse(text);
                },

                copySRS() {
                    navigator.clipboard.writeText(this.srsDocument).then(() => {
                        this.showNotification('SRS copiado para a área de transferência!');
                    }).catch(err => {
                        console.error('Erro ao copiar:', err);
                    });
                },

                showNotification(message) {
                    this.toastMessage = message;
                    this.showToast = true;
                    setTimeout(() => {
                        this.showToast = false;
                    }, 3000);
                },

                updateContext() {
                    // Criar um contexto interno com todas as respostas
                    this.contextData = JSON.parse(JSON.stringify(this.answers));
                },

                hasContext() {
                    // Verificar se há pelo menos uma resposta preenchida
                    for (let category in this.answers) {
                        for (let question in this.answers[category]) {
                            if (this.answers[category][question].trim()) {
                                return true;
                            }
                        }
                    }
                    return false;
                },

                async suggestAnswer(categoryKey, questionId) {
                    // Verificar se há contexto suficiente
                    if (!this.hasContext()) {
                        alert('Por favor, responda pelo menos uma pergunta anterior para gerar sugestões contextuais.');
                        return;
                    }

                    const suggestionKey = `${categoryKey}_${questionId}`;
                    this.loadingSuggestion[suggestionKey] = true;

                    try {
                        const response = await fetch('/teste_agente2/sugerir-resposta', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                current_category: categoryKey,
                                current_question: questionId,
                                context: this.contextData
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Mostrar modal com preview da sugestão
                            this.currentSuggestion = data.suggestion;
                            this.currentSuggestionTarget = { category: categoryKey, question: questionId };
                            this.showSuggestionModal = true;
                        } else {
                            alert(data.error || 'Erro ao gerar sugestão');
                        }
                    } catch (error) {
                        alert('Erro de conexão: ' + error.message);
                    } finally {
                        this.loadingSuggestion[suggestionKey] = false;
                    }
                },

                applySuggestion() {
                    if (this.currentSuggestionTarget) {
                        const { category, question } = this.currentSuggestionTarget;
                        this.answers[category][question] = this.currentSuggestion;
                        this.updateProgress();
                        this.updateContext();
                        this.showNotification('Sugestão aplicada com sucesso!');
                        this.showSuggestionModal = false;
                        this.currentSuggestion = '';
                        this.currentSuggestionTarget = null;
                    }
                },
                
                toggleSpeechRecognition(categoryKey, questionId) {
                    const key = `${categoryKey}_${questionId}`;
                    
                    if (this.isRecording[key]) {
                        this.stopSpeechRecognition(key);
                    } else {
                        this.startSpeechRecognition(categoryKey, questionId);
                    }
                },
                
                startSpeechRecognition(categoryKey, questionId) {
                    const key = `${categoryKey}_${questionId}`;
                    
                    // Verificar suporte
                    if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
                        alert('Seu navegador não suporta reconhecimento de voz. Por favor, use Chrome, Edge ou Safari.');
                        return;
                    }
                    
                    // Criar instância do reconhecimento
                    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                    this.speechRecognition = new SpeechRecognition();
                    
                    // Configurar reconhecimento
                    this.speechRecognition.lang = 'pt-BR';
                    this.speechRecognition.continuous = true;
                    this.speechRecognition.interimResults = true;
                    this.speechRecognition.maxAlternatives = 1;
                    
                    // Marcar como gravando
                    this.isRecording[key] = true;
                    
                    // Armazenar texto temporário
                    let finalTranscript = this.answers[categoryKey][questionId] || '';
                    let interimTranscript = '';
                    
                    // Evento de resultado
                    this.speechRecognition.onresult = (event) => {
                        interimTranscript = '';
                        
                        for (let i = event.resultIndex; i < event.results.length; i++) {
                            const transcript = event.results[i][0].transcript;
                            
                            if (event.results[i].isFinal) {
                                finalTranscript += transcript + ' ';
                            } else {
                                interimTranscript += transcript;
                            }
                        }
                        
                        // Atualizar o campo com o texto reconhecido
                        this.answers[categoryKey][questionId] = (finalTranscript + interimTranscript).trim();
                        this.updateProgress();
                        this.updateContext();
                    };
                    
                    // Evento de erro
                    this.speechRecognition.onerror = (event) => {
                        console.error('Erro no reconhecimento de voz:', event.error);
                        this.isRecording[key] = false;
                        
                        let errorMessage = 'Erro no reconhecimento de voz';
                        switch(event.error) {
                            case 'no-speech':
                                errorMessage = 'Nenhuma fala detectada';
                                break;
                            case 'audio-capture':
                                errorMessage = 'Nenhum microfone encontrado';
                                break;
                            case 'not-allowed':
                                errorMessage = 'Permissão do microfone negada';
                                break;
                        }
                        alert(errorMessage);
                    };
                    
                    // Evento de fim
                    this.speechRecognition.onend = () => {
                        this.isRecording[key] = false;
                    };
                    
                    // Iniciar reconhecimento
                    try {
                        this.speechRecognition.start();
                        this.showNotification('Gravação iniciada. Fale próximo ao microfone.');
                    } catch (e) {
                        console.error('Erro ao iniciar reconhecimento:', e);
                        this.isRecording[key] = false;
                    }
                },
                
                stopSpeechRecognition(key) {
                    if (this.speechRecognition) {
                        this.speechRecognition.stop();
                        this.isRecording[key] = false;
                        this.showNotification('Gravação finalizada.');
                    }
                }
            }
        }
    </script>
</body>
</html>