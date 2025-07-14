<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conhecimento extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
    ];
    
    public function colaboradores()
    {
        return $this->belongsToMany(Colaborador::class, 'colaborador_conhecimento');
    }
}
