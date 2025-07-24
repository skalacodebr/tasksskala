<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContaPagar extends Model
{
    protected $table = 'contas_pagar';

    protected $fillable = [
        'descricao',
        'valor',
        'data_vencimento',
        'data_pagamento',
        'conta_bancaria_id',
        'tipo',
        'parcela_atual',
        'total_parcelas',
        'periodicidade',
        'data_fim_recorrencia',
        'status',
        'categoria',
        'fornecedor',
        'observacoes'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
        'data_fim_recorrencia' => 'date'
    ];

    public function contaBancaria()
    {
        return $this->belongsTo(ContaBancaria::class);
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

    public function scopePagas($query)
    {
        return $query->where('status', 'pago');
    }

    public static function atualizarContasVencidas()
    {
        self::where('status', 'pendente')
            ->whereDate('data_vencimento', '<', now())
            ->update(['status' => 'vencido']);
    }

    public function estaAtrasada()
    {
        return $this->status == 'pendente' && $this->data_vencimento < now();
    }
}
