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
}
