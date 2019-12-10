<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Bill extends Model
{
    use Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'receptionist_id',
        'status',
        'table_id',
        'total_price',
        'customer_name',
        'street',
        'district',
        'city',
        'phone',
        'email',
    ];

    public $sortable = [
        'id',
        'table_id',
        'customer_name',
        'phone',
        'created_at',
        'updated_at',
    ];
}
