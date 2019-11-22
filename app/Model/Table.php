<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'admin_id',
        'area_id',
        'role',
        'first_login',
        'name',
        'image',
        'address',
        'phone',
        'email',
        'password',
    ];
}
