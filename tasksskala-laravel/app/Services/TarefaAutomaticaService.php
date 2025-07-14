<?php

namespace App\Services;

use App\Models\Tarefa;
use App\Models\Projeto;
use App\Models\Colaborador;
use Carbon\Carbon;

class TarefaAutomaticaService
{
    public function gerarTarefasFeedbackProjetosAtivos()
    {
        $projetosAtivos = Projeto::where('status', 'em_andamento')
            ->with(['cliente', 'colaboradorResponsavel'])
            ->get();

        $colaboradoresAtendimento = Colaborador::whereHas('setor', function ($query) {
            $query->where('nome', 'Atendimento');
        })->get();

        if ($colaboradoresAtendimento->isEmpty()) {
            \Log::warning('Nenhum colaborador encontrado no setor Atendimento para gerar tarefas automáticas');
            return;
        }

        foreach ($projetosAtivos as $projeto) {
            $tarefaExistente = Tarefa::where('projeto_id', $projeto->id)
                ->where('tipo', 'automatica_feedback')
                ->where('status', '!=', 'concluida')
                ->first();

            if (!$tarefaExistente) {
                foreach ($colaboradoresAtendimento as $colaborador) {
                    Tarefa::create([
                        'titulo' => "Enviar Feedback do projeto {$projeto->nome} para cliente",
                        'descricao' => "Entrar em contato com o cliente {$projeto->cliente->nome} para coletar feedback sobre o andamento do projeto {$projeto->nome}.",
                        'colaborador_id' => $colaborador->id,
                        'projeto_id' => $projeto->id,
                        'tipo' => 'automatica_feedback',
                        'prioridade' => 'media',
                        'status' => 'pendente',
                        'data_vencimento' => Carbon::now()->addDays(7),
                        'recorrente' => false,
                    ]);
                }
            }
        }
    }

    public function gerarTarefasDiariasAprovacao()
    {
        $projetosAprovacao = Projeto::where('status', 'aprovacao_app')
            ->with(['cliente', 'colaboradorResponsavel'])
            ->get();

        $colaboradoresAtendimento = Colaborador::whereHas('setor', function ($query) {
            $query->where('nome', 'Atendimento');
        })->get();

        if ($colaboradoresAtendimento->isEmpty()) {
            return;
        }

        foreach ($projetosAprovacao as $projeto) {
            $hoje = Carbon::now()->format('Y-m-d');
            
            $tarefaHoje = Tarefa::where('projeto_id', $projeto->id)
                ->where('tipo', 'automatica_aprovacao')
                ->whereDate('created_at', $hoje)
                ->first();

            if (!$tarefaHoje) {
                foreach ($colaboradoresAtendimento as $colaborador) {
                    Tarefa::create([
                        'titulo' => "Verificar status do projeto {$projeto->nome} em aprovação",
                        'descricao' => "Verificar o status de aprovação do projeto {$projeto->nome} e enviar atualização para o cliente {$projeto->cliente->nome}.",
                        'colaborador_id' => $colaborador->id,
                        'projeto_id' => $projeto->id,
                        'tipo' => 'automatica_aprovacao',
                        'prioridade' => 'alta',
                        'status' => 'pendente',
                        'data_vencimento' => Carbon::now()->endOfDay(),
                        'recorrente' => true,
                        'frequencia_recorrencia' => 'diaria',
                    ]);
                }
            }
        }
    }

    public function processarMudancaStatusProjeto(Projeto $projeto, $statusAnterior)
    {
        switch ($projeto->status) {
            case 'em_andamento':
                if ($statusAnterior !== 'em_andamento') {
                    $this->gerarTarefaFeedbackProjeto($projeto);
                }
                break;

            case 'aprovacao_app':
                if ($statusAnterior !== 'aprovacao_app') {
                    $this->gerarTarefaAprovacaoProjeto($projeto);
                }
                break;

            case 'concluido':
            case 'cancelado':
                $this->cancelarTarefasAutomaticasProjeto($projeto);
                break;
        }
    }

    private function gerarTarefaFeedbackProjeto(Projeto $projeto)
    {
        $colaboradoresAtendimento = Colaborador::whereHas('setor', function ($query) {
            $query->where('nome', 'Atendimento');
        })->get();

        foreach ($colaboradoresAtendimento as $colaborador) {
            Tarefa::create([
                'titulo' => "Enviar Feedback do projeto {$projeto->nome} para cliente",
                'descricao' => "Entrar em contato com o cliente {$projeto->cliente->nome} para coletar feedback sobre o andamento do projeto {$projeto->nome}.",
                'colaborador_id' => $colaborador->id,
                'projeto_id' => $projeto->id,
                'tipo' => 'automatica_feedback',
                'prioridade' => 'media',
                'status' => 'pendente',
                'data_vencimento' => Carbon::now()->addDays(7),
                'recorrente' => false,
            ]);
        }
    }

    private function gerarTarefaAprovacaoProjeto(Projeto $projeto)
    {
        $colaboradoresAtendimento = Colaborador::whereHas('setor', function ($query) {
            $query->where('nome', 'Atendimento');
        })->get();

        foreach ($colaboradoresAtendimento as $colaborador) {
            Tarefa::create([
                'titulo' => "Verificar status do projeto {$projeto->nome} em aprovação",
                'descricao' => "Verificar o status de aprovação do projeto {$projeto->nome} e enviar atualização para o cliente {$projeto->cliente->nome}.",
                'colaborador_id' => $colaborador->id,
                'projeto_id' => $projeto->id,
                'tipo' => 'automatica_aprovacao',
                'prioridade' => 'alta',
                'status' => 'pendente',
                'data_vencimento' => Carbon::now()->endOfDay(),
                'recorrente' => true,
                'frequencia_recorrencia' => 'diaria',
            ]);
        }
    }

    private function cancelarTarefasAutomaticasProjeto(Projeto $projeto)
    {
        Tarefa::where('projeto_id', $projeto->id)
            ->whereIn('tipo', ['automatica_feedback', 'automatica_aprovacao'])
            ->whereIn('status', ['pendente', 'em_andamento'])
            ->update([
                'status' => 'cancelada',
                'observacoes' => 'Projeto finalizado/cancelado - tarefa automática cancelada'
            ]);
    }
}