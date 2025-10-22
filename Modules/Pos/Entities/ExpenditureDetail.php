<?php

namespace Modules\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Product;

class ExpenditureDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'parcel_id',
        'production_id',
        'product_name',
        'product_unit',
        'quantity',
        'price',
        'subtotal',
        'discount',
        'created_at',
        'updated_at',
        'type',
        'created_by',
    ];
    protected $table = 'expenditure_detail';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
