<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Product;

class ProductChild extends Model
{
    use HasFactory;

    protected $table = 'product_child';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\ProductChildFactory::new();
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
