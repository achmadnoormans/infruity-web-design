<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory;

    protected $table = 'position';
    protected $primaryKey = 'id';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\PositionFactory::new();
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
