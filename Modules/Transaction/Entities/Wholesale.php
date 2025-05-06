<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DB;

class Wholesale extends Model
{
    use HasFactory;

    protected $table = 'wholesale';
    protected $primaryKey = 'id';
    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Transaction\Database\factories\WholesaleFactory::new();
    }

    public function supplier()
    {
        return $this->belongsTo('Modules\Supplier\Entities\Supplier', 'supplier_id');
    }
    public function products()
    {
        return $this->hasMany('Modules\Transaction\Entities\WholesaleProduct', 'wholesale_id');
    }

    public static function getData()
    {
        return DB::table('view_wholesale')
            ->select('*')
            ->get();
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
}
