<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    protected $table = 'setores';
    
    protected $fillable = [
        'nome',
        'descricao',
    ];
    
    public function colaboradores()
    {
        return $this->hasMany(Colaborador::class);
    }
}
