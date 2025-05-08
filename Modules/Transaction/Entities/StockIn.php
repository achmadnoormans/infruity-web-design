<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockIn extends Model
{
    use HasFactory;

    protected $table = 'stock_in';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Transaction\Database\factories\StockInFactory::new();
    }
}
