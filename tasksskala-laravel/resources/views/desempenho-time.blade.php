@extends('layouts.colaborador')

@section('title', 'Desempenho do Time')

@section('content')
<div class="space-y-6">
    <!-- Header com filtros -->
    <div class="card-dark shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-primary-dark">Desempenho do Time</h1>
                    <p class="text-gray-400 mt-1">Métricas de produtividade e ranking de colaboradores</p>
                </div>
                <form method="GET" action="{{ route('desempenho-time') }}" class="flex items-center space-x-4">
                    <label class="text-sm text-muted-dark">Período:</label>
                    <select name="periodo" onchange="this.form.submit()" class="input-dark rounded-md">
                        <option value="7" {{ request('periodo') == '7' ? 'selected' : '' }}>Últimos 7 dias</option>
                        <option value="30" {{ request('periodo', '30') == '30' ? 'selected' : '' }}>Últimos 30 dias</option>
                        <option value="90" {{ request('periodo') == '90' ? 'selected' : '' }}>Últimos 90 dias</option>
                        <option value="365" {{ request('periodo') == '365' ? 'selected' : '' }}>Último ano</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="card-dark shadow rounded-lg">
        <div class="border-b border-gray-700">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button type="button" 
                        onclick="showTab('metricas')"
                        id="tab-metricas"
                        class="tab-button border-b-2 border-purple-500 py-2 px-1 text-sm font-medium text-purple-600">
                    Métricas de Desempenho
                </button>
                <button type="button" 
                        onclick="showTab('ranking')"
                        id="tab-ranking"
                        class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-muted-dark hover:text-primary-dark hover:border-gray-600">
                    Ranking & Prêmios
                </button>
            </nav>
        </div>

        <!-- Tab Content: Métricas -->
        <div id="content-metricas" class="tab-content p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full table-dark-custom">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">
                                Colaborador
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-muted-dark uppercase tracking-wider">
                                Tarefas Concluídas
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-muted-dark uppercase tracking-wider">
                                Total de Tarefas
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-muted-dark uppercase tracking-wider">
                                Média/Dia
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-muted-dark uppercase tracking-wider">
                                Projetos
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-muted-dark uppercase tracking-wider">
                                Tempo Médio (h)
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-muted-dark uppercase tracking-wider">
                                Pontuação
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($metricas as $index => $metrica)
                            <tr class="{{ $index < 3 ? 'bg-gray-800 bg-opacity-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($index < 3)
                                            <span class="flex-shrink-0 inline-block w-8 h-8 rounded-full {{ $index == 0 ? 'bg-yellow-500' : ($index == 1 ? 'bg-gray-400' : 'bg-orange-600') }} text-white flex items-center justify-center text-sm font-bold mr-3">
                                                {{ $index + 1 }}
                                            </span>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-primary-dark">
                                                {{ $metrica['colaborador']->nome }}
                                            </div>
                                            <div class="text-sm text-muted-dark">
                                                {{ $metrica['colaborador']->setor->nome ?? 'Sem setor' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-lg font-semibold text-green-400">{{ $metrica['tarefas_concluidas'] }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-muted-dark">
                                    {{ $metrica['tarefas_total'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-medium text-blue-400">{{ $metrica['media_por_dia'] }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-muted-dark">
                                    {{ $metrica['projetos_trabalhados'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-muted-dark">
                                    {{ $metrica['tempo_medio'] }}h
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-900 bg-opacity-20 text-purple-400">
                                        {{ $metrica['pontuacao'] }} pts
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Período selecionado -->
            <div class="mt-4 text-sm text-muted-dark text-center">
                Período: {{ $dataInicio->format('d/m/Y') }} até {{ $dataFim->format('d/m/Y') }}
            </div>
        </div>

        <!-- Tab Content: Ranking -->
        <div id="content-ranking" class="tab-content hidden p-6">
            <!-- Ranking Atual -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-primary-dark mb-4">🏆 Ranking Atual</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($rankingAtual as $ranking)
                        <div class="card-dark p-6 text-center {{ $ranking['posicao'] == 1 ? 'border-2 border-yellow-500' : '' }}">
                            <div class="mb-3">
                                @if($ranking['posicao'] == 1)
                                    <span class="text-6xl">🥇</span>
                                @elseif($ranking['posicao'] == 2)
                                    <span class="text-6xl">🥈</span>
                                @else
                                    <span class="text-6xl">🥉</span>
                                @endif
                            </div>
                            <h4 class="text-xl font-bold text-primary-dark mb-2">
                                {{ $ranking['colaborador']->nome }}
                            </h4>
                            <p class="text-2xl font-bold text-purple-400 mb-2">
                                {{ $ranking['pontuacao'] }} pontos
                            </p>
                            @if($ranking['premio'])
                                <div class="mt-4 p-3 bg-gray-800 rounded-lg">
                                    <p class="text-sm font-medium text-green-400">
                                        {{ $ranking['premio']['premio'] }}
                                    </p>
                                    <p class="text-xs text-muted-dark mt-1">
                                        {{ $ranking['premio']['descricao'] }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Prêmios Mensais -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-primary-dark mb-4">📅 Prêmios Mensais</h3>
                    <div class="space-y-3">
                        @foreach($premiosMensais as $posicao => $premio)
                            <div class="card-dark p-4">
                                <div class="flex items-start">
                                    <span class="text-2xl mr-3">
                                        @if($posicao == 1) 🥇
                                        @elseif($posicao == 2) 🥈
                                        @else 🥉
                                        @endif
                                    </span>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-primary-dark">{{ $premio['titulo'] }}</h4>
                                        <p class="text-sm font-bold text-green-400 mt-1">{{ $premio['premio'] }}</p>
                                        <p class="text-xs text-muted-dark mt-1">{{ $premio['descricao'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Prêmios Anuais -->
                <div>
                    <h3 class="text-lg font-semibold text-primary-dark mb-4">🎯 Prêmios Anuais</h3>
                    <div class="space-y-3">
                        @foreach($premiosAnuais as $posicao => $premio)
                            <div class="card-dark p-4 border-2 {{ $posicao == 1 ? 'border-yellow-500' : 'border-gray-600' }}">
                                <div class="flex items-start">
                                    <span class="text-2xl mr-3">
                                        @if($posicao == 1) 🏆
                                        @else 🏅
                                        @endif
                                    </span>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-primary-dark">{{ $premio['titulo'] }}</h4>
                                        <p class="text-sm font-bold {{ $posicao == 1 ? 'text-yellow-400' : 'text-blue-400' }} mt-1">
                                            {{ $premio['premio'] }}
                                        </p>
                                        <p class="text-xs text-muted-dark mt-1">{{ $premio['descricao'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 p-4 bg-blue-900 bg-opacity-20 rounded-lg">
                        <p class="text-sm text-blue-400">
                            <strong>Nota:</strong> Os prêmios anuais são baseados no desempenho acumulado durante todo o ano e serão sorteados em dezembro entre os finalistas.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Nota sobre certificados -->
            <div class="mt-6 p-4 bg-green-900 bg-opacity-20 rounded-lg text-center">
                <p class="text-sm text-green-400">
                    <span class="text-lg">🏅</span> <strong>Todos os 3 primeiros colocados do mês recebem o Certificado de Reconhecimento!</strong> <span class="text-lg">🏅</span>
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    O certificado especial de destaque do mês é entregue para o 1º, 2º e 3º lugar como forma de reconhecimento pelo excelente desempenho.
                </p>
                </div>
            </div>

            <!-- Critérios de Pontuação -->
            <div class="mt-8 p-6 bg-gray-800 rounded-lg">
                <h3 class="text-lg font-semibold text-primary-dark mb-3">📊 Como são calculados os pontos?</h3>
                <ul class="space-y-2 text-sm text-muted-dark">
                    <li>• <span class="text-green-400 font-medium">Tarefas Concluídas:</span> 10 pontos por tarefa</li>
                    <li>• <span class="text-blue-400 font-medium">Média por Dia:</span> 5 pontos por tarefa/dia</li>
                    <li>• <span class="text-purple-400 font-medium">Projetos Trabalhados:</span> 3 pontos por projeto</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-purple-500', 'text-purple-600');
        button.classList.add('border-transparent', 'text-muted-dark');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Set active state on selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.remove('border-transparent', 'text-muted-dark');
    activeTab.classList.add('border-purple-500', 'text-purple-600');
}
</script>
@endsection