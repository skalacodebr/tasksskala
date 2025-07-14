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
    ];

    protected function casts(): array
    {
        return [
            'data_vencimento' => 'datetime',
            'data_inicio' => 'datetime',
            'data_fim' => 'datetime',
            'recorrente' => 'boolean',
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
        ]);
    }

    public function cancelarTarefa($observacoes = null)
    {
        $this->update([
            'status' => 'cancelada',
            'observacoes' => $observacoes,
        ]);
    }

    public function getDuracaoAttribute()
    {
        if ($this->data_inicio && $this->data_fim) {
            return $this->data_inicio->diffInMinutes($this->data_fim);
        }
        return null;
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
}
