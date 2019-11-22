<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bill_id',
        'receptionist_id',
        'status',
        'table_number',
        'total_price',
        'customer_name',
        'street',
        'district',
        'city',
        'phone',
        'email',
    ];
}
