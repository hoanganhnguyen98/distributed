<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'incident_id',
        'content',
        'status',
        'type'
    ];
}
