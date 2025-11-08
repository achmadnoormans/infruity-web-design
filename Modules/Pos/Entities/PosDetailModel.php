<?php

namespace Modules\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Product;
use Illuminate\Database\Eloquent\SoftDeletes;

class PosDetailModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [];
    protected $table = 'pos_transaction_detail';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function parcel()
    {
        return $this->belongsTo(Product::class, 'parcel_id');
    }

}
