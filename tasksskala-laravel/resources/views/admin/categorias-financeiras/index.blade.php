@extends($layout ?? 'layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Categorias Financeiras</h1>
        <a href="{{ route('admin.categorias-financeiras.create') }}" class="btn-primary-dark font-bold py-2 px-4 rounded">
            Nova Categoria
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

    <div class="card-dark shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-700 table-dark-custom">
            <thead class="bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Tipo de Custo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Cor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="card-dark divide-y divide-gray-200">
                @forelse($categorias as $categoria)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded mr-2" style="background-color: {{ $categoria->cor }}"></div>
                                {{ $categoria->nome }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $categoria->tipo == 'entrada' ? 'bg-green-900 text-green-200' : 'bg-red-900 text-red-200' }}">
                                {{ ucfirst($categoria->tipo) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($categoria->tipo_custo)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-900 text-blue-200">
                                    {{ ucfirst($categoria->tipo_custo) }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-primary-dark">{{ $categoria->cor }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $categoria->ativo ? 'bg-green-900 text-green-200' : 'bg-red-900 text-red-200' }}">
                                {{ $categoria->ativo ? 'Ativa' : 'Inativa' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.categorias-financeiras.show', $categoria->id) }}" class="text-indigo-400 hover:text-indigo-300 mr-3">Ver</a>
                            <a href="{{ route('admin.categorias-financeiras.edit', $categoria->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Editar</a>
                            <form action="{{ route('admin.categorias-financeiras.destroy', $categoria->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-muted-dark">
                            Nenhuma categoria cadastrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categorias->links() }}
    </div>
</div>
@endsection