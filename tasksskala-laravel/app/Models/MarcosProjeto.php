<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarcosProjeto extends Model
{
    use HasFactory;

    protected $table = 'marcos_projeto';

    protected $fillable = [
        'projeto_id',
        'nome',
        'descricao',
        'prazo',
        'valor',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'prazo' => 'date',
            'valor' => 'decimal:2',
        ];
    }

    public function projeto()
    {
        return $this->belongsTo(Projeto::class);
    }
}
