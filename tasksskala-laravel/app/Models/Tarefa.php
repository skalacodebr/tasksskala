<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tarefa extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'colaborador_id',
        'created_by',
        'projeto_id',
        'tipo',
        'prioridade',
        'status',
        'data_vencimento',
        'data_inicio',
        'data_fim',
        'observacoes',
        'recorrente',
        'frequencia_recorrencia',
        'plano_dia',
        'pomodoros',
        'criar_tarefa_teste',
        'testador_id',
        'tarefa_origem_id',
        'tarefa_teste_id',
        'notas',
        'data_pausa',
        'tempo_pausado',
        'pausada',
        'transferido_de_id',
        'transferido_para_id',
        'data_transferencia',
        'motivo_transferencia',
    ];

    protected function casts(): array
    {
        return [
            'data_vencimento' => 'datetime',
            'data_inicio' => 'datetime',
            'data_fim' => 'datetime',
            'recorrente' => 'boolean',
            'plano_dia' => 'date',
            'pomodoros' => 'array',
            'criar_tarefa_teste' => 'boolean',
            'data_pausa' => 'datetime',
            'pausada' => 'boolean',
            'data_transferencia' => 'datetime',
        ];
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class);
    }

    public function projeto()
    {
        return $this->belongsTo(Projeto::class);
    }

    public function iniciarTarefa()
    {
        $this->update([
            'status' => 'em_andamento',
            'data_inicio' => now(),
        ]);
    }

    public function concluirTarefa($observacoes = null)
    {
        $this->update([
            'status' => 'concluida',
            'data_fim' => now(),
            'observacoes' => $observacoes,
            'pausada' => false,
        ]);
    }

    public function cancelarTarefa($observacoes = null)
    {
        $this->update([
            'status' => 'cancelada',
            'observacoes' => $observacoes,
        ]);
    }

    public function pausarTarefa()
    {
        if ($this->status === 'em_andamento' && !$this->pausada) {
            $this->update([
                'pausada' => true,
                'data_pausa' => now(),
            ]);
        }
    }

    public function continuarTarefa()
    {
        if ($this->status === 'em_andamento' && $this->pausada) {
            $tempoPausado = now()->diffInSeconds($this->data_pausa);
            $this->update([
                'pausada' => false,
                'tempo_pausado' => $this->tempo_pausado + $tempoPausado,
                'data_pausa' => null,
            ]);
        }
    }

    public function adicionarNota($nota)
    {
        $notaFormatada = now()->format('d/m/Y H:i') . ' - ' . $nota;
        $notasAtuais = $this->notas ? $this->notas . "\n\n" . $notaFormatada : $notaFormatada;
        
        $this->update([
            'notas' => $notasAtuais,
        ]);
    }

    public function getDuracaoAttribute()
    {
        if ($this->data_inicio && $this->data_fim) {
            return $this->data_inicio->diffInMinutes($this->data_fim);
        }
        return null;
    }

    public function getDuracaoFormatadaAttribute()
    {
        if (!$this->duracao) {
            return null;
        }
        
        $totalMinutos = round($this->duracao);
        $horas = floor($totalMinutos / 60);
        $minutosRestantes = $totalMinutos % 60;
        
        if ($horas > 0) {
            return sprintf('%dh %dm', $horas, $minutosRestantes);
        }
        
        return sprintf('%dm', $minutosRestantes);
    }

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeEmAndamento($query)
    {
        return $query->where('status', 'em_andamento');
    }

    public function scopeConcluidas($query)
    {
        return $query->where('status', 'concluida');
    }

    public function scopeAtrasadas($query)
    {
        return $query->where('data_vencimento', '<', now())
                    ->whereIn('status', ['pendente', 'em_andamento']);
    }

    public function scopeAutomaticas($query)
    {
        return $query->whereIn('tipo', ['automatica_feedback', 'automatica_aprovacao']);
    }

    public function testador()
    {
        return $this->belongsTo(Colaborador::class, 'testador_id');
    }

    public function tarefaOrigem()
    {
        return $this->belongsTo(Tarefa::class, 'tarefa_origem_id');
    }

    public function tarefaTeste()
    {
        return $this->belongsTo(Tarefa::class, 'tarefa_teste_id');
    }

    public function transferidoDe()
    {
        return $this->belongsTo(Colaborador::class, 'transferido_de_id');
    }

    public function transferidoPara()
    {
        return $this->belongsTo(Colaborador::class, 'transferido_para_id');
    }

    public function transferencias()
    {
        return $this->hasMany(TarefaTransferencia::class);
    }

    public function criador()
    {
        return $this->belongsTo(Colaborador::class, 'created_by');
    }

    public function transferirResponsabilidade($paraColaboradorId, $motivo)
    {
        $deColaboradorId = $this->colaborador_id;
        
        // Criar registro de transferência
        TarefaTransferencia::create([
            'tarefa_id' => $this->id,
            'de_colaborador_id' => $deColaboradorId,
            'para_colaborador_id' => $paraColaboradorId,
            'motivo' => $motivo,
        ]);
        
        // Atualizar a tarefa
        $this->update([
            'colaborador_id' => $paraColaboradorId,
            'transferido_de_id' => $deColaboradorId,
            'transferido_para_id' => $paraColaboradorId,
            'data_transferencia' => now(),
            'motivo_transferencia' => $motivo,
        ]);
        
        // Adicionar nota sobre a transferência
        $colaboradorDe = Colaborador::find($deColaboradorId);
        $colaboradorPara = Colaborador::find($paraColaboradorId);
        $nota = "Tarefa transferida de {$colaboradorDe->nome} para {$colaboradorPara->nome}. Motivo: {$motivo}";
        $this->adicionarNota($nota);
        
        return $this;
    }
}
