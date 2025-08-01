<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KanbanVenda extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'ordem',
        'cor'
    ];

    public function cards()
    {
        return $this->hasMany(KanbanCard::class)->orderBy('ordem');
    }
}
