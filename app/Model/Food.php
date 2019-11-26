<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Food extends Model
{
    use Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id',
        'name',
        'image',
        'type',
        'source',
        'material',
        'vnd_price',
        'usd_price',
    ];

    public $sortable = [
        'name',
        'type',
        'source',
    ];
}
