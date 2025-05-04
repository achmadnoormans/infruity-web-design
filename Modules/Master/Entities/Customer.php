<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DB;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\CustomerFactory::new();
    }

    public static function getCustomerNumber()
    {
        $orderData = self::select(DB::raw('CAST(RIGHT(code, 5) AS UNSIGNED) + 1 AS order_number'))
            ->whereRaw('MONTH(created_at) = MONTH(NOW())')
            ->whereRaw('YEAR(created_at) = YEAR(NOW())')
            ->whereRaw('SUBSTRING(code, 1, 3) = ?', ['PLG'])
            ->orderByRaw('CAST(RIGHT(code, 5) AS UNSIGNED) DESC')
            ->limit(1)
            ->first();
            $orderPad = '00001';
        if ($orderData && $orderData->order_number) {
            $orderPad = str_pad($orderData->order_number, 5, '0', STR_PAD_LEFT);
        }

        $prefix = 'PLG' . now()->format('ym');
        $newCode = $prefix . $orderPad;

        return $newCode;
    }
}
