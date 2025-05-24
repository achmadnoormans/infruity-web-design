<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Position;
use Modules\Master\Entities\Department;
use App\Models\User;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';
    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\StaffFactory::new();
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }
}
