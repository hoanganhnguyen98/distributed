<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id',
        'action',
        'doing_ids',
        'pending_ids',
        'create_id',
        'support_ids'
    ];
}
