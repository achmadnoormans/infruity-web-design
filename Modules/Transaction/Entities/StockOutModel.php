<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Product;
use Modules\Transaction\Entities\StockOutType;
use Illuminate\Support\Facades\DB;

class StockOutModel extends Model
{
    use HasFactory;

    protected $table = 'stock_out_transaction';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Transaction\Database\factories\StockOutModelFactory::new();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function type()
    {
        return $this->belongsTo(StockOutType::class);
    }

    public static function getOrderNumber()
    {
        $orderData = self::select(DB::raw('CAST(RIGHT(code, 3) AS UNSIGNED) + 1 AS code'))
            ->whereRaw('MONTH(created_at) = MONTH(NOW())')
            ->whereRaw('YEAR(created_at) = YEAR(NOW())')
            ->whereRaw('SUBSTRING(code, 1, 2) = ?', ['SO'])
            ->orderByRaw('CAST(RIGHT(code, 3) AS UNSIGNED) DESC')
            ->limit(1)
            ->first();
            $orderPad = '001';
        if ($orderData && $orderData->code) {
            $orderPad = str_pad($orderData->code, 3, '0', STR_PAD_LEFT);
        }

        $prefix = 'SO' . now()->format('ym');
        $newCode = $prefix . $orderPad;

        return $newCode;
    }
}
