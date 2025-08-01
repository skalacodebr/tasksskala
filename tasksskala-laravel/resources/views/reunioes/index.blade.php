@extends('layouts.colaborador')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Área de Reuniões</h1>
            
            <div class="row">
                <!-- Botão Análise de Requisitos -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-clipboard-list fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">Análise de Requisitos</h5>
                            <p class="card-text">Gere documentos de análise de requisitos para seus projetos</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAnaliseRequisitos">
                                <i class="fas fa-plus"></i> Novo Documento
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Adicione outros botões aqui no futuro -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Análise de Requisitos -->
<div class="modal fade" id="modalAnaliseRequisitos" tabindex="-1" aria-labelledby="modalAnaliseRequisitosLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAnaliseRequisitosLabel">Selecione o Projeto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAnaliseRequisitos">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="projeto_id" class="form-label">Projeto</label>
                        <select class="form-select" id="projeto_id" name="projeto_id" required>
                            <option value="">Selecione um projeto...</option>
                            @foreach($projetos as $projeto)
                                <option value="{{ $projeto->id }}">{{ $projeto->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> Continuar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formAnaliseRequisitos = document.getElementById('formAnaliseRequisitos');
    
    formAnaliseRequisitos.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const projetoId = document.getElementById('projeto_id').value;
        
        if (!projetoId) {
            alert('Por favor, selecione um projeto');
            return;
        }
        
        fetch('{{ route("reunioes.analise-requisitos.gerar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                projeto_id: projetoId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect_url;
            } else {
                alert('Erro ao processar solicitação');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao processar solicitação');
        });
    });
});
</script>
@endsection