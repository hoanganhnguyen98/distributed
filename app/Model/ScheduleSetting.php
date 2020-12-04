<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ScheduleSetting extends Model
{
    protected $casts = [
        'off_days' => 'array',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'month',
        'year',
        'off_saturday',
        'off_sunday',
        'off_days'
    ];
}
