<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductHppRunning extends Model
{
    use HasFactory;

    protected $table = 'product_hpp';
    protected $fillable = [];
}
