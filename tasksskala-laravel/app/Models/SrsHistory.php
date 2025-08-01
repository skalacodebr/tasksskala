<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SrsHistory extends Model
{
    protected $fillable = [
        'session_id',
        'version',
        'answers',
        'srs_document',
        'ip_address',
        'user_agent',
        'projeto_id'
    ];
    
    protected $casts = [
        'answers' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Get formatted creation date
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }
    
    /**
     * Get project name from answers
     */
    public function getProjectNameAttribute()
    {
        if ($this->version === 'v2') {
            return $this->answers['proposito_escopo']['objetivo_principal'] ?? 'Sem nome';
        } else {
            // For v1, get from first answer or summary
            return array_values($this->answers)[0] ?? 'Sem nome';
        }
    }
    
    /**
     * Relationship with Projeto
     */
    public function projeto()
    {
        return $this->belongsTo(Projeto::class);
    }
}
