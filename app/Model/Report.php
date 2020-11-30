<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'create_id',
        'task_id',
        'content',
        'status',
        'type',
        'title',
        'image',
    ];
}
