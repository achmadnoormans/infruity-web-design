<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\User;

class Branch extends Model
{
    use HasFactory;
    protected $table = 'branch';
    protected $fillable = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_branch', 'branch_id', 'user_id');
    }

    protected static function booted()
    {
        static::addGlobalScope('userBranch', function ($query) {
            if (auth()->check()) {
                if (Session('role')['id_role'] != 1) {
                    $userId = auth()->id();
                    $query->whereHas('users', function ($q) use ($userId) {
                        $q->where('users.id_user', $userId);
                    });
                }
            }
        });
    }
}
