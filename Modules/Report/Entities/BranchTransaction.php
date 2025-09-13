<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class BranchTransaction extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    public static function getAllBranchTransaction($start, $end)
    {
        $result = DB::select("CALL get_branch_report(?, ?)", [$start, $end]);
        return $result;
    }
}
