<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Product;
use Modules\Master\Entities\Supplier;
use Modules\Master\Entities\ProductUnit;
use Modules\Master\Entities\ProductCategory;
use Modules\Transaction\Entities\ProductStock;

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
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    public function productUnit()
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id', 'id');
    }

    public function productStock()
    {
        return $this->belongsTo(ProductStock::class, 'product_id', 'id');
    }
}
