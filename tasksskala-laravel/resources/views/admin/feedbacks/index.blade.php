@extends('layouts.admin')

@section('title', 'Gerenciar Feedbacks')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">Gerenciar Feedbacks</h1>
            <a href="{{ route('admin.feedbacks.estatisticas') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Estatísticas
            </a>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total de Feedbacks</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pendentes</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $estatisticas['pendentes'] }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Respondidos</p>
                    <p class="text-2xl font-bold text-green-600">{{ $estatisticas['respondidos'] }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Média Avaliação</p>
                    <p class="text-2xl font-bold text-purple-600">
                        {{ $estatisticas['media_avaliacao'] ? number_format($estatisticas['media_avaliacao'], 1) : '-' }}
                        <span class="text-lg text-gray-500">/ 5</span>
                    </p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <form method="GET" action="{{ route('admin.feedbacks.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="em_analise" {{ request('status') == 'em_analise' ? 'selected' : '' }}>Em Análise</option>
                        <option value="respondido" {{ request('status') == 'respondido' ? 'selected' : '' }}>Respondido</option>
                        <option value="resolvido" {{ request('status') == 'resolvido' ? 'selected' : '' }}>Resolvido</option>
                        <option value="arquivado" {{ request('status') == 'arquivado' ? 'selected' : '' }}>Arquivado</option>
                    </select>
                </div>

                <!-- Tipo -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                    <select name="tipo" id="tipo" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="sugestao" {{ request('tipo') == 'sugestao' ? 'selected' : '' }}>Sugestão</option>
                        <option value="reclamacao" {{ request('tipo') == 'reclamacao' ? 'selected' : '' }}>Reclamação</option>
                        <option value="elogio" {{ request('tipo') == 'elogio' ? 'selected' : '' }}>Elogio</option>
                        <option value="duvida" {{ request('tipo') == 'duvida' ? 'selected' : '' }}>Dúvida</option>
                        <option value="outro" {{ request('tipo') == 'outro' ? 'selected' : '' }}>Outro</option>
                    </select>
                </div>

                <!-- Prioridade -->
                <div>
                    <label for="prioridade" class="block text-sm font-medium text-gray-700 mb-1">Prioridade</label>
                    <select name="prioridade" id="prioridade" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todas</option>
                        <option value="baixa" {{ request('prioridade') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                        <option value="media" {{ request('prioridade') == 'media' ? 'selected' : '' }}>Média</option>
                        <option value="alta" {{ request('prioridade') == 'alta' ? 'selected' : '' }}>Alta</option>
                        <option value="urgente" {{ request('prioridade') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                    </select>
                </div>

                <!-- Cliente -->
                <div>
                    <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                    <select name="cliente_id" id="cliente_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Projeto -->
                <div>
                    <label for="projeto_id" class="block text-sm font-medium text-gray-700 mb-1">Projeto</label>
                    <select name="projeto_id" id="projeto_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach($projetos as $projeto)
                            <option value="{{ $projeto->id }}" {{ request('projeto_id') == $projeto->id ? 'selected' : '' }}>
                                {{ $projeto->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Busca -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Assunto ou mensagem"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('admin.feedbacks.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    Limpar Filtros
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Tabela de Feedbacks -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Cliente / Projeto
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tipo / Prioridade
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Assunto
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Data
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Avaliação
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($feedbacks as $feedback)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $feedback->cliente->nome }}</div>
                            @if($feedback->projeto)
                                <div class="text-sm text-gray-500">{{ $feedback->projeto->nome }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <!-- Tipo -->
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($feedback->tipo == 'reclamacao') bg-red-100 text-red-800
                                        @elseif($feedback->tipo == 'sugestao') bg-blue-100 text-blue-800
                                        @elseif($feedback->tipo == 'elogio') bg-green-100 text-green-800
                                        @elseif($feedback->tipo == 'duvida') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($feedback->tipo) }}
                            </span>
                            <!-- Prioridade -->
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                  style="background-color: {{ $feedback->cor_prioridade }}20; color: {{ $feedback->cor_prioridade }};">
                                {{ ucfirst($feedback->prioridade) }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ Str::limit($feedback->assunto, 50) }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($feedback->mensagem, 60) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                   @if($feedback->status == 'pendente') bg-yellow-100 text-yellow-800
                                   @elseif($feedback->status == 'em_analise') bg-blue-100 text-blue-800
                                   @elseif($feedback->status == 'respondido') bg-green-100 text-green-800
                                   @elseif($feedback->status == 'resolvido') bg-purple-100 text-purple-800
                                   @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $feedback->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $feedback->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($feedback->avaliacao)
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $feedback->avaliacao ? 'text-yellow-400' : 'text-gray-300' }}" 
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.007 3.104a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        @else
                            <span class="text-sm text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.feedbacks.show', $feedback) }}" class="text-blue-600 hover:text-blue-900">
                            Ver Detalhes
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Nenhum feedback encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if($feedbacks->hasPages())
        <div class="mt-6">
            {{ $feedbacks->links() }}
        </div>
    @endif
</div>
@endsection