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

    public function product()
    {
        return $this->belongsTo(Product::class, 'free_product_id');
    }
}
