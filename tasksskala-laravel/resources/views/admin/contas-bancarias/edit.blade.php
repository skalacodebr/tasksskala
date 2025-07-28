@extends($layout ?? 'layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Editar Conta Bancária</h1>

        <form action="{{ route('admin.contas-bancarias.update', $conta->id) }}" method="POST" class="card-dark shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="nome">
                    Nome da Conta
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('nome') border-red-500 @enderror" 
                       id="nome" 
                       type="text" 
                       name="nome" 
                       value="{{ old('nome', $conta->nome) }}" 
                       required>
                @error('nome')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="banco">
                    Banco
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('banco') border-red-500 @enderror" 
                       id="banco" 
                       type="text" 
                       name="banco" 
                       value="{{ old('banco', $conta->banco) }}" 
                       required>
                @error('banco')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="agencia">
                    Agência (opcional)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('agencia') border-red-500 @enderror" 
                       id="agencia" 
                       type="text" 
                       name="agencia" 
                       value="{{ old('agencia', $conta->agencia) }}">
                @error('agencia')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="conta">
                    Número da Conta
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('conta') border-red-500 @enderror" 
                       id="conta" 
                       type="text" 
                       name="conta" 
                       value="{{ old('conta', $conta->conta) }}" 
                       required>
                @error('conta')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="tipo_conta">
                    Tipo de Conta
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('tipo_conta') border-red-500 @enderror" 
                        id="tipo_conta" 
                        name="tipo_conta" 
                        required>
                    <option value="corrente" {{ old('tipo_conta', $conta->tipo_conta) == 'corrente' ? 'selected' : '' }}>Conta Corrente</option>
                    <option value="poupanca" {{ old('tipo_conta', $conta->tipo_conta) == 'poupanca' ? 'selected' : '' }}>Conta Poupança</option>
                </select>
                @error('tipo_conta')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="saldo_atual">
                    Saldo Atual
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('saldo_atual') border-red-500 @enderror" 
                       id="saldo_atual" 
                       type="number" 
                       step="0.01" 
                       name="saldo_atual" 
                       value="{{ old('saldo_atual', $conta->saldo_atual) }}">
                @error('saldo_atual')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-muted-dark text-sm font-bold mb-2">
                    <input type="checkbox" name="ativo" value="1" {{ old('ativo', $conta->ativo) ? 'checked' : '' }}>
                    Conta Ativa
                </label>
            </div>

            <div class="mb-6">
                <label class="block text-muted-dark text-sm font-bold mb-2" for="observacoes">
                    Observações
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline @error('observacoes') border-red-500 @enderror" 
                          id="observacoes" 
                          name="observacoes" 
                          rows="3">{{ old('observacoes', $conta->observacoes) }}</textarea>
                @error('observacoes')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button class="btn-primary-dark font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Atualizar
                </button>
                <a href="{{ route('admin.contas-bancarias.index') }}" class="text-muted-dark hover:text-primary-dark">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection