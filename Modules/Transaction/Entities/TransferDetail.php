<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Product;

class TransferDetail extends Model
{
    use HasFactory;

    protected $table = 'transfer_detail';
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
