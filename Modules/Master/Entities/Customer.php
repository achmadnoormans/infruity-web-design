<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\CustomerFactory::new();
    }
}
