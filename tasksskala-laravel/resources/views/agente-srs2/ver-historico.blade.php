<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRS #{{ $historico->id }} - Histórico</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <style>
        .prose {
            max-width: none;
        }
        .prose h1 { @apply text-3xl font-bold mt-8 mb-4; }
        .prose h2 { @apply text-2xl font-semibold mt-6 mb-3; }
        .prose h3 { @apply text-xl font-semibold mt-4 mb-2; }
        .prose h4 { @apply text-lg font-medium mt-3 mb-2; }
        .prose p { @apply mb-4; }
        .prose ul { @apply list-disc list-inside mb-4; }
        .prose ol { @apply list-decimal list-inside mb-4; }
        .prose li { @apply mb-2; }
        .prose code { @apply bg-gray-100 px-1 py-0.5 rounded text-sm; }
        .prose pre { @apply bg-gray-100 p-4 rounded-lg mb-4 overflow-x-auto; }
        .prose blockquote { @apply border-l-4 border-gray-300 pl-4 italic my-4; }
        .prose table { @apply min-w-full mb-4; }
        .prose th { @apply bg-gray-50 px-4 py-2 text-left font-semibold; }
        .prose td { @apply border-t px-4 py-2; }
        .prose strong { @apply font-semibold; }
        .prose em { @apply italic; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">SRS #{{ $historico->id }}</h1>
                        <p class="text-gray-600 mt-1">Gerado em {{ $historico->formatted_date }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('agente-srs2.historico') }}" 
                           class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                            Voltar ao Histórico
                        </a>
                        <form action="{{ route('agente-srs2.download') }}" method="GET" class="inline">
                            <input type="hidden" name="history_id" value="{{ $historico->id }}">
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                Baixar Markdown
                            </button>
                        </form>
                        <button onclick="copiarSRS()" 
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                            Copiar SRS
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-semibold">IP:</span> {{ $historico->ip_address }}
                    </div>
                    <div>
                        <span class="font-semibold">Sessão:</span> {{ substr($historico->session_id, 0, 8) }}...
                    </div>
                </div>
            </div>

            <!-- Respostas do Formulário -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Respostas do Formulário</h2>
                <div class="space-y-4">
                    @php
                        $questions = app(\App\Http\Controllers\AgenteSRS2Controller::class)->getQuestions();
                    @endphp
                    
                    @foreach($questions as $categoryKey => $category)
                        <div class="border-b pb-4">
                            <h3 class="font-semibold text-lg mb-2">{{ $category['title'] }}</h3>
                            @foreach($category['questions'] as $question)
                                @if(isset($historico->answers[$categoryKey][$question['id']]))
                                    <div class="mb-3">
                                        <p class="text-sm font-medium text-gray-700">{{ $question['question'] }}</p>
                                        @if($question['type'] === 'dynamic_functions' && $question['id'] === 'principais_funcoes')
                                            @php
                                                $functionalities = json_decode($historico->answers[$categoryKey][$question['id']], true);
                                            @endphp
                                            @if(is_array($functionalities))
                                                <div class="mt-1 space-y-2">
                                                    @foreach($functionalities as $index => $func)
                                                        <div class="bg-gray-50 p-3 rounded-md">
                                                            <p class="font-medium">Funcionalidade {{ $index + 1 }}: {{ $func['name'] }}</p>
                                                            @if(!empty($func['workflow']))
                                                                <p class="text-sm mt-1"><strong>Fluxo:</strong> {{ $func['workflow'] }}</p>
                                                            @endif
                                                            @if(!empty($func['sequences']))
                                                                <p class="text-sm mt-1"><strong>Sequências:</strong> {{ $func['sequences'] }}</p>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-sm text-gray-600 mt-1">{{ $historico->answers[$categoryKey][$question['id']] }}</p>
                                            @endif
                                        @else
                                            <p class="text-sm text-gray-600 mt-1">{{ $historico->answers[$categoryKey][$question['id']] }}</p>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Documento SRS -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Documento SRS Gerado</h2>
                <div id="srs-content" class="prose max-w-none"></div>
            </div>
        </div>
    </div>

    <script>
        // Renderizar Markdown
        const srsDocument = @json($historico->srs_document);
        document.getElementById('srs-content').innerHTML = marked.parse(srsDocument);
        
        // Função para copiar SRS
        function copiarSRS() {
            navigator.clipboard.writeText(srsDocument).then(() => {
                alert('SRS copiado para a área de transferência!');
            }).catch(err => {
                console.error('Erro ao copiar:', err);
                alert('Erro ao copiar o SRS');
            });
        }
    </script>
</body>
</html>