<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Product;

class SortirDetail extends Model
{
    use HasFactory;

    protected $table = 'sortir_transaction_detail';
    protected $fillable = [
        'sortir_id',
        'product_id',
        'price',
        'quantity',
        'discount',
        'subtotal',
        'created_at',
        'created_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
