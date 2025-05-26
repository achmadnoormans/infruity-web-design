<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionParcelDetail extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'production_parcel_detail';

    public function products()
    {
        return $this->belongsTo('Modules\Master\Entities\Product', 'product_id', 'id');
    }
}
