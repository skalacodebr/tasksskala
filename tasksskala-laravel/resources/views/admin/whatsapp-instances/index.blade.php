@extends('layouts.admin')

@section('title', 'Instâncias WhatsApp')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Instâncias WhatsApp</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span>
                    <i class="fab fa-whatsapp me-1"></i>
                    Gerenciar Instâncias
                </span>
                <a href="{{ route('admin.whatsapp-instances.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nova Instância
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nome da Instância</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(is_array($instances) && count($instances) > 0)
                            @foreach($instances as $instance)
                            <tr>
                                <td>{{ $instance['instanceName'] ?? 'N/A' }}</td>
                                <td>
                                    @if(isset($instance['state']) && $instance['state'] == 'open')
                                        <span class="badge bg-success">Conectado</span>
                                    @else
                                        <span class="badge bg-warning">Desconectado</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.whatsapp-instances.show', $instance['instanceName']) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    
                                    @if(isset($instance['state']) && $instance['state'] == 'open')
                                        <form action="{{ route('admin.whatsapp-instances.disconnect', $instance['instanceName']) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-sm btn-warning"
                                                    onclick="return confirm('Desconectar esta instância?')">
                                                <i class="fas fa-unlink"></i> Desconectar
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.whatsapp-instances.destroy', $instance['instanceName']) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Tem certeza que deseja remover esta instância?')">
                                            <i class="fas fa-trash"></i> Remover
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center">Nenhuma instância encontrada</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection