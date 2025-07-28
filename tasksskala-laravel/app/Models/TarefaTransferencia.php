<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TarefaTransferencia extends Model
{
    use HasFactory;

    protected $table = 'tarefa_transferencias';

    protected $fillable = [
        'tarefa_id',
        'de_colaborador_id',
        'para_colaborador_id',
        'motivo',
    ];

    public function tarefa()
    {
        return $this->belongsTo(Tarefa::class);
    }

    public function deColaborador()
    {
        return $this->belongsTo(Colaborador::class, 'de_colaborador_id');
    }

    public function paraColaborador()
    {
        return $this->belongsTo(Colaborador::class, 'para_colaborador_id');
    }
}
