<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'area_id',
        'admin_id',
        'name',
        'description',
    ];
}
