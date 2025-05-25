<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Production extends Model
{
    use HasFactory;

    protected $table = 'production';
    protected $primaryKey = 'id';
    protected $fillable = [];
    
    public static function getOrderNumber()
    {
        $orderData = self::select(DB::raw('CAST(RIGHT(production_number, 3) AS UNSIGNED) + 1 AS production_number'))
            ->whereRaw('MONTH(created_at) = MONTH(NOW())')
            ->whereRaw('YEAR(created_at) = YEAR(NOW())')
            ->whereRaw('SUBSTRING(production_number, 1, 2) = ?', ['PO'])
            ->orderByRaw('CAST(RIGHT(production_number, 3) AS UNSIGNED) DESC')
            ->limit(1)
            ->first();
            $orderPad = '001';
        if ($orderData && $orderData->production_number) {
            $orderPad = str_pad($orderData->production_number, 3, '0', STR_PAD_LEFT);
        }

        $prefix = 'PRO' . now()->format('Ym');
        $newCode = $prefix . $orderPad;

        return $newCode;
    }
}
