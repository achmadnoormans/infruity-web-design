<?php

namespace Modules\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Master\Entities\Customer;

class PosModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'date',
        'total',
        'dicount',
        'created_by'
    ];
    protected $table = 'pos_transaction';

    public function details(): HasMany
    {
        return $this->hasMany(PosDetailModel::class, 'pos_id', 'id');
    }

    // Total Quantity
    public function getTotalQuantityAttribute()
    {
        return $this->details->sum('quantity');
    }

    // Total Price
    public function getTotalPriceAttribute()
    {
        return $this->details->sum('price');
    }

    // Total Discount
    public function getTotalDiscountAttribute()
    {
        return $this->details->sum('discount');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
