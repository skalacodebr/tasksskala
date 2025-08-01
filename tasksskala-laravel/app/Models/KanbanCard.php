<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KanbanCard extends Model
{
    protected $fillable = [
        'titulo',
        'descricao',
        'kanban_venda_id',
        'cliente_id',
        'colaborador_id',
        'valor',
        'data_previsao',
        'data_conclusao',
        'ordem',
        'observacoes'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_previsao' => 'date',
        'data_conclusao' => 'date',
    ];

    public function kanbanVenda()
    {
        return $this->belongsTo(KanbanVenda::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class);
    }
}
