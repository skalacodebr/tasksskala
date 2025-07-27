<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'workflow',
        'current_step',
        'collected_data',
        'conversation_history',
        'status',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'workflow' => 'array',
        'collected_data' => 'array',
        'conversation_history' => 'array',
        'current_step' => 'integer',
    ];

    protected $attributes = [
        'status' => 'active',
        'current_step' => 0,
        'collected_data' => '[]',
        'conversation_history' => '[]',
    ];
}