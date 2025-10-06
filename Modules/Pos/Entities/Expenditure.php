<?php

namespace Modules\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\Master\Entities\Branch;
use Modules\Pos\Entities\ExpenditurePayment;
use Modules\Pos\Entities\User;
use Modules\Master\Entities\PaymentMethod;

class Expenditure extends Model
{
    use HasFactory;

    protected $table = 'expenditure';
    protected $fillable = [
        'uuid',
        'branch_id',
        'invoice_number',
        'date',
        'paid',
        'payment_method',
        'total',
        'status',
        'type',
        'created_by'
    ];

    public static function getOrderNumber()
    {
        $orderData = self::select(DB::raw('CAST(RIGHT(invoice_number, 3) AS UNSIGNED) + 1 AS order_number'))
            ->whereRaw('MONTH(created_at) = MONTH(NOW())')
            ->whereRaw('YEAR(created_at) = YEAR(NOW())')
            ->whereRaw('SUBSTRING(invoice_number, 1, 3) = ?', ['EXP'])
            ->orderByRaw('CAST(RIGHT(invoice_number, 3) AS UNSIGNED) DESC')
            ->limit(1)
            ->first();
        $orderPad = '001';
        if ($orderData && $orderData->order_number) {
            $orderPad = str_pad($orderData->order_number, 3, '0', STR_PAD_LEFT);
        }

        $prefix = 'EXP' . now()->format('Ym');
        $newCode = $prefix . $orderPad;

        return $newCode;
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function payment()
    {
        return $this->hasMany(ExpenditurePayment::class, 'expenditure_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method', 'id');
    }
}
