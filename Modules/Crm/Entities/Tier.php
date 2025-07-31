<?php

namespace Modules\Crm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Product;

class Tier extends Model
{
    use HasFactory;

    protected $table = 'crm_tier';
    protected $fillable = [];
    protected $casts = [
        'free_product_id' => 'array',
    ];

    public function freeProducts()
    {
        return Product::whereIn('id', $this->free_product_id)->with('category', 'get_stock', 'unit');
    }

    // public function product()
    // {
    //     return $this->belongsTo(Product::class, 'free_product_id');
    // }

    // public function birthday()
    // {
    //     return $this->belongsTo(Product::class, 'birthday_gift');
    // }
}
