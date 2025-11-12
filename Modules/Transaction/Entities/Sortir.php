<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Sortir extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'date',
        'invoice_number',
        'subtotal',
        'total',
        'status',
        'type',
        'created_by',
    ];
    protected $table = 'sortir_transaction';
    
    public function detail()
    {
        return $this->hasMany(SortirDetail::class, 'sortir_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id_user');
    }

    public static function getOrderNumber()
    {
        $orderData = self::select(DB::raw('CAST(RIGHT(invoice_number, 3) AS UNSIGNED) + 1 AS order_number'))
            ->whereRaw('MONTH(created_at) = MONTH(NOW())')
            ->whereRaw('YEAR(created_at) = YEAR(NOW())')
            ->whereRaw('SUBSTRING(invoice_number, 1, 3) = ?', ['SOR'])
            ->orderByRaw('CAST(RIGHT(invoice_number, 3) AS UNSIGNED) DESC')
            ->limit(1)
            ->first();
        $orderPad = '001';
        if ($orderData && $orderData->order_number) {
            $orderPad = str_pad($orderData->order_number, 3, '0', STR_PAD_LEFT);
        }

        $prefix = 'SOR' . now()->format('Ym');
        $newCode = $prefix . $orderPad;

        return $newCode;
    }
}
