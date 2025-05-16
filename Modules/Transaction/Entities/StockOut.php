<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockOut extends Model
{
    use HasFactory;

    protected $table = 'stock_out';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Transaction\Database\factories\StockOutFactory::new();
    }
}
