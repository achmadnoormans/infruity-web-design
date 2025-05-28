<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\ProductCategory;
use Modules\Master\Entities\ProductUnit;
use Modules\Transaction\Entities\ProductStock;
use DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'products';

    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\ProductFactory::new();
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }
    public function unit()
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit');
    }

    public function productStock()
    {
        return $this->belongsTo(ProductStock::class, 'product_id', 'id');
    }

    public function get_stock()
    {
        return $this->belongsTo('Modules\Transaction\Entities\ProductStock', 'id', 'id');
    }
}
