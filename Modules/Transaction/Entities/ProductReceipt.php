<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductReceipt extends Model
{
    use HasFactory;

    protected $table = 'product_receipt';
    protected $fillable = [
        'receipt_id',
        'product_id',
        'product_receipt_id',
        'quantity',
        'created_at',
        'updated_at',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Transaction\Database\factories\ProductReceiptFactory::new();
    }

    public function product()
    {
        return $this->belongsTo('Modules\Master\Entities\Product', 'product_id', 'id');
    }

    public function ingredients()
    {
        return $this->belongsTo('Modules\Master\Entities\Product', 'product_receipt_id', 'id');
    }
}
