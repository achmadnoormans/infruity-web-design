<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class CustomerTransaction extends Model
{
    use HasFactory;

    protected $table = 'vw_customer_transaction';
    protected $fillable = [];
    
    public static function getAllCustomerTransaction($start, $end)
    {
        $result = DB::select("CALL get_customer_report(?, ?)", [$start, $end]);
        return $result;
    }
}
