<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaFinanceira extends Model
{
    protected $table = 'categorias_financeiras';
    
    protected $fillable = [
        'nome',
        'tipo',
        'tipo_custo',
        'cor',
        'descricao',
        'ativo'
    ];
    
    protected $casts = [
        'ativo' => 'boolean'
    ];
    
    public function contasPagar()
    {
        return $this->hasMany(ContaPagar::class, 'categoria_id');
    }
    
    public function contasReceber()
    {
        return $this->hasMany(ContaReceber::class, 'categoria_id');
    }
    
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }
    
    public function scopeSaidas($query)
    {
        return $query->where('tipo', 'saida');
    }
    
    public function scopeEntradas($query)
    {
        return $query->where('tipo', 'entrada');
    }
}
