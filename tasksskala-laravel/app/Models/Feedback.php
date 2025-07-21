<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'projeto_id',
        'tipo',
        'prioridade',
        'assunto',
        'mensagem',
        'resposta',
        'respondido_em',
        'respondido_por',
        'status',
        'avaliacao',
    ];

    protected function casts(): array
    {
        return [
            'respondido_em' => 'datetime',
            'avaliacao' => 'integer',
        ];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function projeto()
    {
        return $this->belongsTo(Projeto::class);
    }

    public function respondidoPor()
    {
        return $this->belongsTo(Colaborador::class, 'respondido_por');
    }

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeRespondidos($query)
    {
        return $query->where('status', 'respondido');
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopePorPrioridade($query, $prioridade)
    {
        return $query->where('prioridade', $prioridade);
    }

    public function getCorPrioridadeAttribute()
    {
        return match($this->prioridade) {
            'urgente' => '#DC2626',
            'alta' => '#F59E0B',
            'media' => '#3B82F6',
            'baixa' => '#10B981',
            default => '#6B7280'
        };
    }

    public function getIconeTipoAttribute()
    {
        return match($this->tipo) {
            'sugestao' => 'lightbulb',
            'reclamacao' => 'exclamation-circle',
            'elogio' => 'star',
            'duvida' => 'question-circle',
            'outro' => 'chat-bubble-left-right',
            default => 'document-text'
        };
    }
}
