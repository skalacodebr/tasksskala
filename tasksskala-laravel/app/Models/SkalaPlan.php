<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkalaPlan extends Model
{
    protected $connection = 'skala_tasks';
    protected $table = 'plans';
    
    protected $fillable = [
        'task_id',
        'plan_json',
        'approved'
    ];

    protected $casts = [
        'plan_json' => 'array',
        'approved' => 'boolean'
    ];

    public function task()
    {
        return $this->belongsTo(SkalaTask::class, 'task_id');
    }
}