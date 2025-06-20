<?php

namespace Modules\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Master\Entities\Customer;
use Modules\Master\Entities\PaymentMethod;
use Illuminate\Support\Facades\DB;

class PosModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_number',
        'date',
        'total',
        'dicount',
        'status',
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

    public function payment()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method');
    }


    public static function getOrderNumber()
    {
        $orderData = self::select(DB::raw('CAST(RIGHT(invoice_number, 3) AS UNSIGNED) + 1 AS order_number'))
            ->whereRaw('MONTH(created_at) = MONTH(NOW())')
            ->whereRaw('YEAR(created_at) = YEAR(NOW())')
            ->whereRaw('SUBSTRING(invoice_number, 1, 3) = ?', ['INV'])
            ->orderByRaw('CAST(RIGHT(invoice_number, 3) AS UNSIGNED) DESC')
            ->limit(1)
            ->first();
            $orderPad = '001';
        if ($orderData && $orderData->order_number) {
            $orderPad = str_pad($orderData->order_number, 3, '0', STR_PAD_LEFT);
        }

        $prefix = 'INV' . now()->format('Ym');
        $newCode = $prefix . $orderPad;

        return $newCode;
    }
}
