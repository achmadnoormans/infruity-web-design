<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Product;
use Modules\Master\Entities\Branch;

class ProductBranch extends Model
{
    use HasFactory;

    protected $table = 'product_branch';
    protected $fillable = [
        'product_id',
        'branch_id',
        'price',
        'created_at',
        'updated_at',
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
