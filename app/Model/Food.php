<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'food_id',
        'admin_id',
        'name',
        'image',
        'type',
        'source',
        'material',
        'vnd_price',
        'usa_price',
    ];
}
