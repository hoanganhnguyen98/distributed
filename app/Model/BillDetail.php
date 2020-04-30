<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BillDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bill_id',
        'status',
        'food_id',
        'number',
        'food_name',
        'price',
        'user_id',
        'image'
    ];
}
