@extends('layouts.admin')

@section('title', 'Estatísticas de Feedbacks')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center">
            <a href="{{ route('admin.feedbacks.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Estatísticas de Feedbacks</h1>
        </div>
    </div>

    <!-- Cards de Estatísticas Gerais -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-600 mb-2">Total de Feedbacks</p>
                <p class="text-3xl font-bold text-gray-900">{{ $estatisticasGerais['total_feedbacks'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-600 mb-2">Pendentes</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $estatisticasGerais['pendentes'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-600 mb-2">Respondidos</p>
                <p class="text-3xl font-bold text-green-600">{{ $estatisticasGerais['respondidos'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-600 mb-2">Tempo Médio Resposta</p>
                <p class="text-2xl font-bold text-blue-600">{{ $estatisticasGerais['tempo_medio_resposta'] ?? '-' }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-600 mb-2">Média Avaliação</p>
                <p class="text-3xl font-bold text-purple-600">
                    {{ $estatisticasGerais['media_avaliacao'] ? number_format($estatisticasGerais['media_avaliacao'], 1) : '-' }}
                </p>
                <p class="text-sm text-gray-500">de 5.0</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-600 mb-2">Total Avaliações</p>
                <p class="text-3xl font-bold text-indigo-600">{{ $estatisticasGerais['total_avaliacoes'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico por Tipo -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Distribuição por Tipo</h2>
            <div class="space-y-4">
                @php
                    $tipoLabels = [
                        'sugestao' => 'Sugestão',
                        'reclamacao' => 'Reclamação',
                        'elogio' => 'Elogio',
                        'duvida' => 'Dúvida',
                        'outro' => 'Outro'
                    ];
                    $tipoColors = [
                        'sugestao' => 'bg-blue-500',
                        'reclamacao' => 'bg-red-500',
                        'elogio' => 'bg-green-500',
                        'duvida' => 'bg-purple-500',
                        'outro' => 'bg-gray-500'
                    ];
                    $maxTipo = $porTipo->max() ?: 1;
                @endphp
                @foreach($tipoLabels as $tipo => $label)
                    @php $valor = $porTipo[$tipo] ?? 0; @endphp
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            <span class="text-sm text-gray-600">{{ $valor }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-6">
                            <div class="{{ $tipoColors[$tipo] }} h-6 rounded-full flex items-center justify-center text-white text-xs font-medium"
                                 style="width: {{ $valor > 0 ? ($valor / $maxTipo * 100) : 0 }}%">
                                {{ $valor > 0 ? round($valor / $estatisticasGerais['total_feedbacks'] * 100) . '%' : '' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Gráfico por Prioridade -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Distribuição por Prioridade</h2>
            <div class="space-y-4">
                @php
                    $prioridadeLabels = [
                        'baixa' => 'Baixa',
                        'media' => 'Média',
                        'alta' => 'Alta',
                        'urgente' => 'Urgente'
                    ];
                    $prioridadeColors = [
                        'baixa' => 'bg-green-500',
                        'media' => 'bg-blue-500',
                        'alta' => 'bg-yellow-500',
                        'urgente' => 'bg-red-500'
                    ];
                    $maxPrioridade = $porPrioridade->max() ?: 1;
                @endphp
                @foreach($prioridadeLabels as $prioridade => $label)
                    @php $valor = $porPrioridade[$prioridade] ?? 0; @endphp
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            <span class="text-sm text-gray-600">{{ $valor }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-6">
                            <div class="{{ $prioridadeColors[$prioridade] }} h-6 rounded-full flex items-center justify-center text-white text-xs font-medium"
                                 style="width: {{ $valor > 0 ? ($valor / $maxPrioridade * 100) : 0 }}%">
                                {{ $valor > 0 ? round($valor / $estatisticasGerais['total_feedbacks'] * 100) . '%' : '' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Gráfico por Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Distribuição por Status</h2>
            <div class="space-y-4">
                @php
                    $statusLabels = [
                        'pendente' => 'Pendente',
                        'em_analise' => 'Em Análise',
                        'respondido' => 'Respondido',
                        'resolvido' => 'Resolvido',
                        'arquivado' => 'Arquivado'
                    ];
                    $statusColors = [
                        'pendente' => 'bg-yellow-500',
                        'em_analise' => 'bg-blue-500',
                        'respondido' => 'bg-green-500',
                        'resolvido' => 'bg-purple-500',
                        'arquivado' => 'bg-gray-500'
                    ];
                    $maxStatus = $porStatus->max() ?: 1;
                @endphp
                @foreach($statusLabels as $status => $label)
                    @php $valor = $porStatus[$status] ?? 0; @endphp
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            <span class="text-sm text-gray-600">{{ $valor }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-6">
                            <div class="{{ $statusColors[$status] }} h-6 rounded-full flex items-center justify-center text-white text-xs font-medium"
                                 style="width: {{ $valor > 0 ? ($valor / $maxStatus * 100) : 0 }}%">
                                {{ $valor > 0 ? round($valor / $estatisticasGerais['total_feedbacks'] * 100) . '%' : '' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Evolução por Mês -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Evolução por Mês</h2>
            <div class="space-y-3">
                @forelse($porMes as $mes)
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm font-medium text-gray-700">{{ $mes['mes'] }}</span>
                        <span class="text-sm bg-blue-100 text-blue-800 px-3 py-1 rounded-full">{{ $mes['total'] }} feedbacks</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">Nenhum dado disponível</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Clientes -->
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Top 10 Clientes com Mais Feedbacks</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total de Feedbacks</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topClientes as $index => $cliente)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $cliente->nome }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cliente->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $cliente->feedbacks_count }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Nenhum cliente com feedbacks</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Feedbacks Recentes -->
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Feedbacks Recentes</h2>
        <div class="space-y-4">
            @forelse($recentes as $feedback)
            <div class="border-b pb-4 last:border-b-0">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">{{ $feedback->assunto }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($feedback->mensagem, 100) }}</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="text-xs text-gray-500">
                                <strong>Cliente:</strong> {{ $feedback->cliente->nome }}
                            </span>
                            @if($feedback->projeto)
                            <span class="text-xs text-gray-500">
                                <strong>Projeto:</strong> {{ $feedback->projeto->nome }}
                            </span>
                            @endif
                            <span class="text-xs text-gray-500">
                                {{ $feedback->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($feedback->tipo == 'reclamacao') bg-red-100 text-red-800
                                    @elseif($feedback->tipo == 'sugestao') bg-blue-100 text-blue-800
                                    @elseif($feedback->tipo == 'elogio') bg-green-100 text-green-800
                                    @elseif($feedback->tipo == 'duvida') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($feedback->tipo) }}
                        </span>
                        <a href="{{ route('admin.feedbacks.show', $feedback) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                            Ver detalhes →
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center">Nenhum feedback recente</p>
            @endforelse
        </div>
    </div>
</div>
@endsection