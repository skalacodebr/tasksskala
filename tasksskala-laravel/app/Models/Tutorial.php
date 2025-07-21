<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tutorial extends Model
{
    use HasFactory;

    protected $table = 'tutoriais';

    protected $fillable = [
        'titulo',
        'descricao',
        'arquivo_video',
        'publico_alvo',
        'ativo',
        'ordem',
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeParaColaboradores($query)
    {
        return $query->where('publico_alvo', 'colaboradores');
    }

    public function scopeParaClientes($query)
    {
        return $query->where('publico_alvo', 'clientes');
    }

    public function scopeOrdenados($query)
    {
        return $query->orderBy('ordem', 'asc')->orderBy('titulo', 'asc');
    }
}
