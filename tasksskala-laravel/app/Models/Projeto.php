<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Projeto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'repositorio_git',
        'colaborador_responsavel_id',
        'cliente_id',
        'prazo',
        'anotacoes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'prazo' => 'date',
        ];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function colaboradorResponsavel()
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_responsavel_id');
    }

    public function marcos()
    {
        return $this->hasMany(MarcosProjeto::class);
    }

    public function tarefas()
    {
        return $this->hasMany(Tarefa::class);
    }
}
