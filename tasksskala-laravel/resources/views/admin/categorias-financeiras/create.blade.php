@extends($layout ?? 'layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Nova Categoria Financeira</h1>

        <form action="{{ route('admin.categorias-financeiras.store') }}" method="POST" class="card-dark shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="nome">
                    Nome da Categoria
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('nome') border-red-500 @enderror" 
                       id="nome" 
                       type="text" 
                       name="nome" 
                       value="{{ old('nome') }}" 
                       required>
                @error('nome')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="tipo">
                    Tipo
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('tipo') border-red-500 @enderror" 
                        id="tipo" 
                        name="tipo" 
                        required onchange="mostrarTipoCusto()">
                    <option value="">Selecione...</option>
                    <option value="entrada" {{ old('tipo') == 'entrada' ? 'selected' : '' }}>Entrada (Receitas)</option>
                    <option value="saida" {{ old('tipo') == 'saida' ? 'selected' : '' }}>Saída (Despesas)</option>
                </select>
                @error('tipo')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4" id="campo-tipo-custo" style="display: none;">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="tipo_custo_id">
                    Tipo de Custo (para despesas)
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('tipo_custo_id') border-red-500 @enderror" 
                        id="tipo_custo_id" 
                        name="tipo_custo_id">
                    <option value="">Selecione...</option>
                    @foreach($tiposCusto as $tipo)
                        <option value="{{ $tipo->id }}" {{ old('tipo_custo_id') == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->nome }}
                        </option>
                    @endforeach
                </select>
                @error('tipo_custo_id')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
                <div class="mt-2">
                    <a href="{{ route('admin.tipos-custo.create') }}" class="text-sm text-blue-400 hover:text-blue-300">
                        Gerenciar Tipos de Custo
                    </a>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="cor">
                    Cor (para gráficos)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('cor') border-red-500 @enderror" 
                       id="cor" 
                       type="color" 
                       name="cor" 
                       value="{{ old('cor', '#6B7280') }}" 
                       required>
                @error('cor')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="descricao">
                    Descrição
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('descricao') border-red-500 @enderror" 
                          id="descricao" 
                          name="descricao" 
                          rows="3">{{ old('descricao') }}</textarea>
                @error('descricao')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-muted-dark text-sm font-bold mb-2">
                    <input type="checkbox" name="ativo" value="1" {{ old('ativo', true) ? 'checked' : '' }}>
                    Categoria Ativa
                </label>
            </div>

            <div class="flex items-center justify-between">
                <button class="btn-primary-dark font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Cadastrar
                </button>
                <a href="{{ route('admin.categorias-financeiras.index') }}" class="text-muted-dark hover:text-primary-dark">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function mostrarTipoCusto() {
    const tipo = document.getElementById('tipo').value;
    const campoTipoCusto = document.getElementById('campo-tipo-custo');
    
    if (tipo === 'saida') {
        campoTipoCusto.style.display = 'block';
    } else {
        campoTipoCusto.style.display = 'none';
        document.getElementById('tipo_custo').value = '';
    }
}

// Mostrar campo se necessário ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    mostrarTipoCusto();
});
</script>
@endsection