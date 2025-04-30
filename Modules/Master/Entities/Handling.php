<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Handling extends Model
{
    use HasFactory;

    protected $table = 'handling';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\HandlingFactory::new();
    }
}
