<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class CustomerProduct extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    public static function getAllCustomerProduct($start, $end)
    {
        $result = DB::select("CALL get_customer_product_transaction(?, ?)", [$start, $end]);
        return $result;
    }

}
