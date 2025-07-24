<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'titulo',
        'descricao',
        'setor',
        'prioridade',
        'status',
        'projeto_id',
        'atribuido_para',
        'respondido_em',
        'fechado_em'
    ];

    protected $casts = [
        'respondido_em' => 'datetime',
        'fechado_em' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function projeto()
    {
        return $this->belongsTo(Projeto::class);
    }

    public function atribuidoPara()
    {
        return $this->belongsTo(Colaborador::class, 'atribuido_para');
    }

    public function mensagens()
    {
        return $this->hasMany(TicketMensagem::class);
    }

    public function getSetorLabelAttribute()
    {
        return [
            'comercial' => 'Comercial',
            'financeiro' => 'Financeiro',
            'desenvolvimento' => 'Desenvolvimento'
        ][$this->setor] ?? $this->setor;
    }

    public function getPrioridadeLabelAttribute()
    {
        return [
            'baixa' => 'Baixa',
            'media' => 'Média',
            'alta' => 'Alta'
        ][$this->prioridade] ?? $this->prioridade;
    }

    public function getStatusLabelAttribute()
    {
        return [
            'aberto' => 'Aberto',
            'em_andamento' => 'Em Andamento',
            'respondido' => 'Respondido',
            'fechado' => 'Fechado'
        ][$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        return [
            'aberto' => 'bg-blue-100 text-blue-800',
            'em_andamento' => 'bg-yellow-100 text-yellow-800',
            'respondido' => 'bg-purple-100 text-purple-800',
            'fechado' => 'bg-gray-100 text-gray-800'
        ][$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getPrioridadeColorAttribute()
    {
        return [
            'baixa' => 'bg-green-100 text-green-800',
            'media' => 'bg-yellow-100 text-yellow-800',
            'alta' => 'bg-red-100 text-red-800'
        ][$this->prioridade] ?? 'bg-gray-100 text-gray-800';
    }
}