<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_type_id',
        'status',
        'incident_id',
        'employee_ids',
        'active_ids'
    ];
}
