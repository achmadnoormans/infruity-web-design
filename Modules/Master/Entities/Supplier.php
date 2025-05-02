<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\SupplierFactory::new();
    }
}
