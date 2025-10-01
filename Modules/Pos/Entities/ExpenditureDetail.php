<?php

namespace Modules\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Product;

class ExpenditureDetail extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'expenditure_detail';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
