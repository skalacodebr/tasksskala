@extends($layout ?? 'layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="card-dark rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-700">
                <div class="flex justify-between items-center">
                    <h1 class="text-xl font-semibold">Importar Arquivo OFX</h1>
                    <a href="{{ route('admin.importacao-ofx.index') }}" class="text-muted-dark hover:text-primary-dark">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('admin.importacao-ofx.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-6">
                        <label for="arquivo_ofx" class="block text-sm font-medium text-muted-dark mb-2">Arquivo OFX</label>
                        <input type="file" class="w-full rounded-md border-gray-600 @error('arquivo_ofx') border-red-500 @enderror" 
                               id="arquivo_ofx" name="arquivo_ofx" accept=".ofx,.xml,.txt" required>
                        @error('arquivo_ofx')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-muted-dark">
                            Formatos aceitos: .ofx, .xml, .txt (máximo 5MB)
                        </p>
                    </div>

                    <div class="mb-6">
                        <label for="tipo_conta" class="block text-sm font-medium text-muted-dark mb-2">Tipo de Conta</label>
                        <select class="w-full rounded-md border-gray-600 @error('tipo_conta') border-red-500 @enderror" 
                                id="tipo_conta" name="tipo_conta" required>
                            <option value="">Selecione o tipo</option>
                            <option value="pagar" {{ old('tipo_conta', request('tipo_conta')) == 'pagar' ? 'selected' : '' }}>
                                Contas a Pagar
                            </option>
                            <option value="receber" {{ old('tipo_conta', request('tipo_conta')) == 'receber' ? 'selected' : '' }}>
                                Contas a Receber
                            </option>
                        </select>
                        @error('tipo_conta')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-muted-dark">
                            Selecione se as transações do arquivo são contas a pagar ou a receber
                        </p>
                    </div>

                    <div class="bg-blue-900 border border-blue-700 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-semibold text-blue-200 mb-2">Como funciona a importação:</h3>
                        <ul class="text-sm text-blue-300 space-y-1">
                            <li>• O sistema tentará conciliar automaticamente as transações com contas existentes</li>
                            <li>• Transações não conciliadas automaticamente ficarão pendentes para revisão manual</li>
                            <li>• Você poderá vincular manualmente ou criar novas contas para as transações pendentes</li>
                        </ul>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.importacao-ofx.index') }}" class="px-4 py-2 border border-gray-600 rounded-md text-muted-dark hover:bg-gray-800">
                            Cancelar
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            <i class="fas fa-upload mr-2"></i>Importar Arquivo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection