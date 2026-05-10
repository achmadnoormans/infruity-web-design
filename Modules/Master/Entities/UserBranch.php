<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\Branch;

class UserBranch extends Model
{
    use HasFactory;

    protected $table = 'user_branch';
    protected $fillable = [
        'user_id',
        'branch_id',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public static function getUserBranch()
    {
        // if (session('role')['id_role'] == 1) {
        //     return Branch::pluck('id')->toArray();
        // }

        return self::where('user_id', auth()->user()->id_user)
            ->pluck('branch_id')
            ->toArray();
    }
}
