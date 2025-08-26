<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DB;
use App\Models\User;
use Modules\Crm\Entities\CustomerTier;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $fillable = [
        'name',
        'address',
        'phone',
    ];

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

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    public function customerTier()
    {
        return $this->belongsTo(CustomerTier::class, 'id', 'customer_id');
    }

    public static function getCustomerGraph()
    {
        $results = DB::select("
            WITH RECURSIVE days AS (
                SELECT 1 AS day
                UNION ALL
                SELECT day + 1 FROM days
                WHERE day + 1 <= DAY(LAST_DAY(CURDATE()))
            ),
            daily_counts AS (
                SELECT DAY(created_at) AS day, COUNT(*) AS total
                FROM customer
                WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())
                GROUP BY DAY(created_at)
            )
            SELECT 
                days.day AS hari,
                COALESCE(daily_counts.total, 0) AS total
            FROM days
            LEFT JOIN daily_counts ON days.day = daily_counts.day
            ORDER BY days.day
        ");

        return $results;
    }
}
