<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_id',
        'product_id',
        'quantity',
        'created_by',
        'updated_by'
    ];
    protected $table = 'production_detail';
    protected $primaryKey = 'id';

    protected static function newFactory()
    {
        return \Modules\Transaction\Database\factories\ProductionDetailFactory::new();
    }

    public function products()
    {
        return $this->belongsTo('Modules\Master\Entities\Product', 'product_id', 'id');
    }
}
