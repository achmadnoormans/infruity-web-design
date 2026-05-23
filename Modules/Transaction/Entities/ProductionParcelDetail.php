<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionParcelDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_id',
        'pos_id',
        'product_id',
        'kemasan_id',
        'quantity',
        'quantity_kemasan',
        'price',
        'price_awal',
    ];
    protected $table = 'production_parcel_detail';

    public function product()
    {
        return $this->belongsTo('Modules\Master\Entities\Product', 'product_id', 'id');
    }
}
