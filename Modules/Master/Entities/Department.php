<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $table = 'department';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\DepartmentFactory::new();
    }
}
