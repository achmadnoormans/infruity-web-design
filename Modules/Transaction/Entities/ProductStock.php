<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductStock extends Model
{
    use HasFactory;

    protected $table = 'product_stock';
    protected $fillable = [];
    public $timestamps = false; // kalau tabel nggak pakai created_at, updated_at
    
    
    protected static function newFactory()
    {
        return \Modules\Transaction\Database\factories\ProductStockFactory::new();
    }
}
