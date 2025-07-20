<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkalaTask extends Model
{
    protected $connection = 'skala_tasks';
    protected $table = 'tasks';
    
    protected $fillable = [
        'repository_id',
        'user_id',
        'repository_url',
        'task_description',
        'status'
    ];

    public function plans()
    {
        return $this->hasMany(SkalaPlan::class, 'task_id');
    }
}