<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Colaborador extends Model
{
    protected $table = 'colaboradores';
    
    protected $fillable = [
        'nome',
        'email',
        'senha',
        'setor_id',
    ];
    
    protected $hidden = [
        'senha',
    ];
    
    public function setSenhaAttribute($value)
    {
        $this->attributes['senha'] = Hash::make($value);
    }
    
    public function setor()
    {
        return $this->belongsTo(Setor::class);
    }
    
    public function conhecimentos()
    {
        return $this->belongsToMany(Conhecimento::class, 'colaborador_conhecimento');
    }

    public function tarefas()
    {
        return $this->hasMany(Tarefa::class);
    }

    public function projetosResponsavel()
    {
        return $this->hasMany(Projeto::class, 'colaborador_responsavel_id');
    }

    public function projetosComoResponsavel()
    {
        return $this->belongsToMany(Projeto::class, 'projeto_responsaveis', 'colaborador_id', 'projeto_id')
                    ->withTimestamps();
    }

    public function googleOAuthToken()
    {
        return $this->hasOne(GoogleOAuthToken::class);
    }
}
