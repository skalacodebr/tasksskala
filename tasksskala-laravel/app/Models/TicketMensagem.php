<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMensagem extends Model
{
    use HasFactory;

    protected $table = 'ticket_mensagens';

    protected $fillable = [
        'ticket_id',
        'mensagem',
        'cliente_id',
        'colaborador_id',
        'is_internal'
    ];

    protected $casts = [
        'is_internal' => 'boolean'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class);
    }

    public function getAutorAttribute()
    {
        if ($this->cliente_id) {
            return $this->cliente->nome;
        } elseif ($this->colaborador_id) {
            return $this->colaborador->nome;
        }
        return 'Sistema';
    }

    public function isFromCliente()
    {
        return $this->cliente_id !== null;
    }

    public function isFromColaborador()
    {
        return $this->colaborador_id !== null;
    }
}