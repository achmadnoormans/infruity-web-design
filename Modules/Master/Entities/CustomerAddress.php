<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerAddress extends Model
{
    use HasFactory;


    protected $table = 'customer_address';
    protected $fillable = [
        'customer_id',
        'address',
    ];

}
