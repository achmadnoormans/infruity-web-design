<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'products';
    
    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\ProductFactory::new();
    }
}
