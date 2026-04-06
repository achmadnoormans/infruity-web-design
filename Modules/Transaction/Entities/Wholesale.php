<?php

namespace Modules\Transaction\Entities;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\Branch;
use Modules\Master\Entities\UserBranch;

class Wholesale extends Model
{
    use HasFactory;

    protected $table = 'wholesale';
    protected $primaryKey = 'id';
    protected $fillable = [
        'order_number',
        'branch_id',
        'supplier_id',
        'order_date',
        'status',
        'total_amount',
        'description',
        'created_by',
        'updated_by',
    ];

    protected static function newFactory()
    {
        return \Modules\Transaction\Database\factories\WholesaleFactory::new ();
    }

    public function supplier()
    {
        return $this->belongsTo('Modules\Supplier\Entities\Supplier', 'supplier_id');
    }
    public function products()
    {
        return $this->hasMany('Modules\Transaction\Entities\WholesaleProduct', 'wholesale_id');
    }
    // Alias for products (to match the detail variable name used in views)
    public function getDetailAttribute()
    {
        return $this->products;
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public static function getData($request)
    {
        $query = DB::table('view_wholesale')
            ->leftJoin('branch', 'branch.id', '=', 'view_wholesale.branch_id')
            ->select('view_wholesale.*', 'branch.name as branch_name')
            ->whereIn('branch_id', UserBranch::getUserBranch())
            ->orderBy('created_at', 'desc');

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('order_date', [$request->start_date, $request->end_date]);
        }
        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        if ($request->cabang_filter && $request->cabang_filter != 'all') {
            $query->where('branch_id', $request->cabang_filter);
        }
        return $query;
    }
    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
    // public function scopeFilter($query, $filters)
    // {
    //     if ($filters['search'] ?? false) {
    //         $query->where('order_date', 'like', '%' . $filters['search'] . '%')
    //             ->orWhereHas('supplier', function ($q) use ($filters) {
    //                 $q->where('name', 'like', '%' . $filters['search'] . '%');
    //             });
    //     }
    //     if ($filters['date_from'] ?? false) {
    //         $query->whereDate('order_date', '>=', $filters['date_from']);
    //     }
    //     if ($filters['date_to'] ?? false) {
    //         $query->whereDate('order_date', '<=', $filters['date_to']);
    //     }
    // }
    // public function scopeWithSupplier($query)
    // {
    //     return $query->with('supplier');
    // }
    // public function scopeWithProducts($query)
    // {
    //     return $query->with('products');
    // }
    // public function scopeWithCreatedBy($query)
    // {
    //     return $query->with('createdBy');
    // }

    public static function getOrderNumber()
    {
        $orderData = self::select(DB::raw('CAST(RIGHT(order_number, 3) AS UNSIGNED) + 1 AS order_number'))
            ->whereRaw('MONTH(created_at) = MONTH(NOW())')
            ->whereRaw('YEAR(created_at) = YEAR(NOW())')
            ->whereRaw('SUBSTRING(order_number, 1, 2) = ?', ['PO'])
            ->orderByRaw('CAST(RIGHT(order_number, 3) AS UNSIGNED) DESC')
            ->limit(1)
            ->first();
        $orderPad = '001';
        if ($orderData && $orderData->order_number) {
            $orderPad = str_pad($orderData->order_number, 3, '0', STR_PAD_LEFT);
        }

        $prefix = 'PO' . now()->format('Ym');
        $newCode = $prefix . $orderPad;

        return $newCode;
    }
}
