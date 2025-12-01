<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\Branch;
use Modules\Transaction\Entities\TransferDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;


class Transfer extends Model
{
    use HasFactory;

    protected $table = 'transfer';
    protected $fillable = [
        'uuid',
        'date',
        'invoice_number',
        'subtotal',
        'total',
        'status',
        'type',
        'branch_id',
        'branch_destination_id',
        'created_by',
    ];
    public function detail()
    {
        return $this->hasMany(TransferDetail::class, 'transfer_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function branchDestination()
    {
        return $this->belongsTo(Branch::class, 'branch_destination_id', 'id');
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
            ->whereRaw('SUBSTRING(invoice_number, 1, 3) = ?', ['TRF'])
            ->orderByRaw('CAST(RIGHT(invoice_number, 3) AS UNSIGNED) DESC')
            ->limit(1)
            ->first();
        $orderPad = '001';
        if ($orderData && $orderData->order_number) {
            $orderPad = str_pad($orderData->order_number, 3, '0', STR_PAD_LEFT);
        }

        $prefix = 'TRF' . now()->format('Ym');
        $newCode = $prefix . $orderPad;

        return $newCode;
    }
}
