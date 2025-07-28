@extends($layout ?? 'layouts.admin')

@section('title', 'Detalhes do Tipo de Custo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detalhes do Tipo de Custo</h1>
            <div>
                <a href="{{ route('admin.tipos-custo.edit', $tipoCusto) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Editar
                </a>
                <a href="{{ route('admin.tipos-custo.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Voltar
                </a>
            </div>
        </div>

        <div class="card-dark shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-muted-dark mb-2">Informações Gerais</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-muted-dark">Nome:</p>
                        <p class="font-medium">{{ $tipoCusto->nome }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-dark">Slug:</p>
                        <p class="font-medium">{{ $tipoCusto->slug }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-dark">Ordem:</p>
                        <p class="font-medium">{{ $tipoCusto->ordem }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-muted-dark">Status:</p>
                        <p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tipoCusto->ativo ? 'bg-green-900 text-green-200' : 'bg-red-900 text-red-200' }}">
                                {{ $tipoCusto->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </p>
                    </div>
                </div>
                
                @if($tipoCusto->descricao)
                <div class="mt-4">
                    <p class="text-sm text-muted-dark">Descrição:</p>
                    <p class="font-medium">{{ $tipoCusto->descricao }}</p>
                </div>
                @endif
            </div>

            <div class="mb-4">
                <h3 class="text-lg font-semibold text-muted-dark mb-2">Categorias Vinculadas</h3>
                @if($tipoCusto->categorias->count() > 0)
                    <div class="bg-gray-800 rounded p-4">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-semibold text-muted-dark uppercase">Nome</th>
                                    <th class="text-left text-xs font-semibold text-muted-dark uppercase">Tipo</th>
                                    <th class="text-left text-xs font-semibold text-muted-dark uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tipoCusto->categorias as $categoria)
                                    <tr>
                                        <td class="py-2">{{ $categoria->nome }}</td>
                                        <td class="py-2">{{ ucfirst($categoria->tipo) }}</td>
                                        <td class="py-2">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $categoria->ativo ? 'bg-green-900 text-green-200' : 'bg-red-900 text-red-200' }}">
                                                {{ $categoria->ativo ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted-dark">Nenhuma categoria vinculada a este tipo de custo.</p>
                @endif
            </div>

            <div class="border-t pt-4">
                <p class="text-sm text-muted-dark">
                    <strong>Criado em:</strong> {{ $tipoCusto->created_at->format('d/m/Y H:i') }}<br>
                    <strong>Atualizado em:</strong> {{ $tipoCusto->updated_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection