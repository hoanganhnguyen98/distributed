<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TaskType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'employee_number',
        'project_type',
        'prioritize',
        'create_id'
    ];
}
