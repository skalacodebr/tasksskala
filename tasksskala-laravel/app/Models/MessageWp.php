<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageWp extends Model
{
    protected $table = 'messages_wp';
    
    protected $fillable = [
        'message_id',
        'remote_jid',
        'from_me',
        'push_name',
        'status',
        'message_text',
        'message_type',
        'media_url',
        'media_type',
        'message_timestamp',
        'instance_id',
        'instance_name',
        'raw_data'
    ];
    
    protected $casts = [
        'from_me' => 'boolean',
        'message_timestamp' => 'integer',
        'raw_data' => 'array',
    ];
}
