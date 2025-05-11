<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Product;
use Modules\Master\Entities\ProductUnit;
use Modules\Master\Entities\ProductCategory;

class WholesaleProduct extends Model
{
    use HasFactory;

    protected $table = 'wholesale_product';
    protected $primaryKey = 'id';
    protected $fillable = ['wholesale_id', 'product_id', 'quantity'];
    
    protected static function newFactory()
    {
        return \Modules\Transaction\Database\factories\WholesaleProductFactory::new();
    }

    public function wholesale()
    {
        return $this->belongsTo(Wholesale::class, 'wholesale_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }
    public function productUnit()
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id', 'id');
    }
}
