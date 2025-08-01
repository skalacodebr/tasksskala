<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContatoWp extends Model
{
    protected $table = 'contatos_wp';
    
    protected $fillable = [
        'remote_jid',
        'push_name',
        'profile_pic_url',
        'instance_id',
        'instance_name',
        'is_group'
    ];
    
    protected $casts = [
        'is_group' => 'boolean',
    ];
    
    public function lastMessage()
    {
        return $this->hasOne(MessageWp::class, 'remote_jid', 'remote_jid')
                    ->whereColumn('messages_wp.instance_name', 'contatos_wp.instance_name')
                    ->latest('message_timestamp');
    }
    
    public function messages()
    {
        return $this->hasMany(MessageWp::class, 'remote_jid', 'remote_jid')
                    ->whereColumn('messages_wp.instance_name', 'contatos_wp.instance_name');
    }
}
