<?php

namespace Modules\Crm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PointDecrement extends Model
{
    use HasFactory;

    protected $table = 'crm_point_decrement';
    protected $fillable = 
    [
        'customer_id',
        'point_decrement',
        'created_by',
        'updated_by',
    ];
}
