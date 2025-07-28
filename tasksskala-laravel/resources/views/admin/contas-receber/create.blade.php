@extends($layout ?? 'layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Nova Conta a Receber</h1>

        <form action="{{ route('admin.contas-receber.store') }}" method="POST" class="card-dark shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="descricao">
                    Descrição
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('descricao') border-red-500 @enderror" 
                       id="descricao" 
                       type="text" 
                       name="descricao" 
                       value="{{ old('descricao') }}" 
                       required>
                @error('descricao')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="cliente_id">
                    Cliente
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('cliente_id') border-red-500 @enderror" 
                        id="cliente_id" 
                        name="cliente_id" 
                        required>
                    <option value="">Selecione um cliente...</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nome }} - {{ $cliente->cpf_cnpj }}
                        </option>
                    @endforeach
                </select>
                @error('cliente_id')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="valor">
                    Valor
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('valor') border-red-500 @enderror" 
                       id="valor" 
                       type="number" 
                       step="0.01" 
                       name="valor" 
                       value="{{ old('valor') }}" 
                       required>
                @error('valor')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="tipo">
                    Tipo de Conta
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('tipo') border-red-500 @enderror" 
                        id="tipo" 
                        name="tipo" 
                        required onchange="mostrarCamposTipo()">
                    <option value="">Selecione...</option>
                    <option value="fixa" {{ old('tipo') == 'fixa' ? 'selected' : '' }}>Fixa</option>
                    <option value="parcelada" {{ old('tipo') == 'parcelada' ? 'selected' : '' }}>Parcelada</option>
                    <option value="recorrente" {{ old('tipo') == 'recorrente' ? 'selected' : '' }}>Recorrente</option>
                </select>
                @error('tipo')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="data_vencimento">
                    Data de Vencimento
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('data_vencimento') border-red-500 @enderror" 
                       id="data_vencimento" 
                       type="date" 
                       name="data_vencimento" 
                       value="{{ old('data_vencimento') }}" 
                       required>
                @error('data_vencimento')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campos para conta parcelada -->
            <div id="campos-parcelada" class="hidden">
                <div class="mb-4">
                    <label class="block text-muted-dark text-sm font-bold mb-2" for="total_parcelas">
                        Número de Parcelas
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('total_parcelas') border-red-500 @enderror" 
                           id="total_parcelas" 
                           type="number" 
                           min="2" 
                           name="total_parcelas" 
                           value="{{ old('total_parcelas') }}">
                    @error('total_parcelas')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Campos para conta recorrente -->
            <div id="campos-recorrente" class="hidden">
                <div class="mb-4">
                    <label class="block text-muted-dark text-sm font-bold mb-2" for="periodicidade">
                        Periodicidade
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('periodicidade') border-red-500 @enderror" 
                            id="periodicidade" 
                            name="periodicidade">
                        <option value="">Selecione...</option>
                        <option value="semanal" {{ old('periodicidade') == 'semanal' ? 'selected' : '' }}>Semanal</option>
                        <option value="mensal" {{ old('periodicidade') == 'mensal' ? 'selected' : '' }}>Mensal</option>
                        <option value="bimestral" {{ old('periodicidade') == 'bimestral' ? 'selected' : '' }}>Bimestral</option>
                        <option value="trimestral" {{ old('periodicidade') == 'trimestral' ? 'selected' : '' }}>Trimestral</option>
                        <option value="semestral" {{ old('periodicidade') == 'semestral' ? 'selected' : '' }}>Semestral</option>
                        <option value="anual" {{ old('periodicidade') == 'anual' ? 'selected' : '' }}>Anual</option>
                    </select>
                    @error('periodicidade')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-muted-dark text-sm font-bold mb-2" for="data_fim_recorrencia">
                        Data Final da Recorrência
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('data_fim_recorrencia') border-red-500 @enderror" 
                           id="data_fim_recorrencia" 
                           type="date" 
                           name="data_fim_recorrencia" 
                           value="{{ old('data_fim_recorrencia') }}">
                    @error('data_fim_recorrencia')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="categoria">
                    Categoria (opcional)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('categoria') border-red-500 @enderror" 
                       id="categoria" 
                       type="text" 
                       name="categoria" 
                       value="{{ old('categoria') }}">
                @error('categoria')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="conta_bancaria_id">
                    Conta Bancária para Recebimento (opcional)
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('conta_bancaria_id') border-red-500 @enderror" 
                        id="conta_bancaria_id" 
                        name="conta_bancaria_id">
                    <option value="">Selecione...</option>
                    @foreach($contasBancarias as $conta)
                        <option value="{{ $conta->id }}" {{ old('conta_bancaria_id') == $conta->id ? 'selected' : '' }}>
                            {{ $conta->nome }} - {{ $conta->banco }}
                        </option>
                    @endforeach
                </select>
                @error('conta_bancaria_id')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="observacoes">
                    Observações
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('observacoes') border-red-500 @enderror" 
                          id="observacoes" 
                          name="observacoes" 
                          rows="3">{{ old('observacoes') }}</textarea>
                @error('observacoes')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button class="btn-primary-dark font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Cadastrar
                </button>
                <a href="{{ route('admin.contas-receber.index') }}" class="text-muted-dark hover:text-primary-dark">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function mostrarCamposTipo() {
    const tipo = document.getElementById('tipo').value;
    document.getElementById('campos-parcelada').classList.add('hidden');
    document.getElementById('campos-recorrente').classList.add('hidden');
    
    if (tipo === 'parcelada') {
        document.getElementById('campos-parcelada').classList.remove('hidden');
    } else if (tipo === 'recorrente') {
        document.getElementById('campos-recorrente').classList.remove('hidden');
    }
}

// Mostrar campos corretos se houver old input
document.addEventListener('DOMContentLoaded', function() {
    mostrarCamposTipo();
});
</script>
@endsection