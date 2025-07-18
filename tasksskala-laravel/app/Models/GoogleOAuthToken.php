<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoogleOAuthToken extends Model
{
    protected $fillable = [
        'colaborador_id',
        'access_token',
        'refresh_token',
        'expires_in',
        'token_created_at',
    ];

    protected $casts = [
        'token_created_at' => 'datetime',
        'expires_in' => 'integer',
    ];

    public function colaborador(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class);
    }

    public function isExpired(): bool
    {
        return $this->token_created_at->addSeconds($this->expires_in)->isPast();
    }
}
