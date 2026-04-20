<?php

namespace Modules\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Master\Entities\Customer;
use Modules\Master\Entities\PaymentMethod;
use Modules\Master\Entities\Staff;
use Modules\Master\Entities\Branch;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;


class PosModel extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'uuid',
        'customer_id',
        'invoice_number',
        'date',
        'total',
        'discount',
        'ongkir',
        'ongkir_discount',
        'ongkir_date',
        'ongkir_time',
        'status',
        'process_status',
        'process_date',
        'note',
        'courier_id',
        'courier_type',
        'ongkir_address',
        'branch_id',
        'branch_process_id',
        'created_by'
    ];
    protected $table = 'pos_transaction';

    public function details(): HasMany
    {
        return $this->hasMany(PosDetailModel::class, 'pos_id', 'id');
    }

    public function paymentDetails(): HasMany
    {
        return $this->hasMany(Payment::class, 'pos_id', 'id');
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

    // Total Payment
    public function getTotalPaymentAttribute()
    {
        return $this->paymentDetails->sum('total');
    }

    // Total Due
    public function getTotalDueAttribute()
    {
        $totalDue = $this->total - $this->getTotalPaymentAttribute();
        return $totalDue > 0 ? $totalDue : 0;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function payment()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method');
    }

    public function courier()
    {
        return $this->belongsTo(Staff::class, 'courier_id');
    }

    public function courierExternal()
    {
        return $this->belongsTo(\Modules\Master\Entities\Kurir::class, 'courier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function branch_proses()
    {
        return $this->belongsTo(Branch::class, 'branch_process_id');
    }


    public static function getOrderNumber()
    {
        $orderData = self::select(DB::raw('CAST(RIGHT(invoice_number, 3) AS UNSIGNED) + 1 AS order_number'))
            ->whereRaw('DAY(created_at) = DAY(NOW())')
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

        $prefix = 'INV' . now()->format('Ymd');
        $newCode = $prefix . $orderPad;

        return $newCode;
    }
}
