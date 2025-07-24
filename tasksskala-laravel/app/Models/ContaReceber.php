<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContaReceber extends Model
{
    protected $table = 'contas_receber';

    protected $fillable = [
        'descricao',
        'valor',
        'data_vencimento',
        'data_recebimento',
        'conta_bancaria_id',
        'cliente_id',
        'tipo',
        'parcela_atual',
        'total_parcelas',
        'periodicidade',
        'data_fim_recorrencia',
        'status',
        'categoria',
        'observacoes'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_vencimento' => 'date',
        'data_recebimento' => 'date',
        'data_fim_recorrencia' => 'date'
    ];

    public function contaBancaria()
    {
        return $this->belongsTo(ContaBancaria::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeVencidas($query)
    {
        return $query->where('status', 'vencido')
                     ->orWhere(function($q) {
                         $q->where('status', 'pendente')
                           ->whereDate('data_vencimento', '<', now());
                     });
    }

    public function scopeRecebidas($query)
    {
        return $query->where('status', 'recebido');
    }
}
