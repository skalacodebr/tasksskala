<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusProjeto extends Model
{
    protected $table = 'status_projetos';
    
    protected $fillable = [
        'nome',
        'cor',
        'descricao',
        'ativo',
        'ordem'
    ];
    
    protected $casts = [
        'ativo' => 'boolean'
    ];
    
    public function projetos()
    {
        return $this->hasMany(Projeto::class, 'status_id');
    }
    
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }
    
    public function scopeOrdenados($query)
    {
        return $query->orderBy('ordem')->orderBy('nome');
    }
}
