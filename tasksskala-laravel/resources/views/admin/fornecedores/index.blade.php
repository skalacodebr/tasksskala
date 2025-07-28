@extends($layout ?? 'layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Fornecedores</h1>
        <a href="{{ route('admin.fornecedores.create') }}" class="btn-primary-dark font-bold py-2 px-4 rounded">
            Novo Fornecedor
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success-dark px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error-dark px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filtros -->
    <form method="GET" class="mb-4 card-dark p-4 rounded-lg shadow">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="busca" value="{{ request('busca') }}" placeholder="Buscar por nome, email ou documento" class="w-full rounded-md border-gray-600">
            </div>
            <div>
                <select name="tipo_pessoa" class="w-full rounded-md border-gray-600">
                    <option value="">Todos os tipos</option>
                    <option value="fisica" {{ request('tipo_pessoa') == 'fisica' ? 'selected' : '' }}>Pessoa Física</option>
                    <option value="juridica" {{ request('tipo_pessoa') == 'juridica' ? 'selected' : '' }}>Pessoa Jurídica</option>
                </select>
            </div>
            <div>
                <select name="status" class="w-full rounded-md border-gray-600">
                    <option value="">Todos os status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Ativos</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inativos</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full">
                    Filtrar
                </button>
            </div>
        </div>
    </form>

    <div class="card-dark shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-700 table-dark-custom">
            <thead class="bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Tipo/Documento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Contato</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Cidade/UF</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Contas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="card-dark divide-y divide-gray-200">
                @forelse($fornecedores as $fornecedor)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-primary-dark">{{ $fornecedor->nome }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-primary-dark">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-800 text-primary-dark">
                                    {{ $fornecedor->tipo_pessoa == 'fisica' ? 'PF' : 'PJ' }}
                                </span>
                                @if($fornecedor->cpf_cnpj)
                                    <span class="text-xs text-muted-dark ml-2">{{ $fornecedor->documento }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-primary-dark">
                                @if($fornecedor->email)
                                    <div>{{ $fornecedor->email }}</div>
                                @endif
                                @if($fornecedor->telefone || $fornecedor->celular)
                                    <div class="text-xs text-muted-dark">
                                        {{ $fornecedor->celular ?: $fornecedor->telefone }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-primary-dark">
                            @if($fornecedor->cidade)
                                {{ $fornecedor->cidade }}{{ $fornecedor->estado ? '/'.$fornecedor->estado : '' }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-primary-dark">
                            <span class="text-xs bg-blue-900 text-blue-200 px-2 py-1 rounded">
                                {{ $fornecedor->contas_pagar_count }} conta(s)
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $fornecedor->ativo ? 'bg-green-900 text-green-200' : 'bg-red-900 text-red-200' }}">
                                {{ $fornecedor->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.fornecedores.show', $fornecedor->id) }}" class="text-indigo-400 hover:text-indigo-300 mr-3">Ver</a>
                            <a href="{{ route('admin.fornecedores.edit', $fornecedor->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Editar</a>
                            <form action="{{ route('admin.fornecedores.destroy', $fornecedor->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-muted-dark">
                            Nenhum fornecedor cadastrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $fornecedores->withQueryString()->links() }}
    </div>
</div>
@endsection