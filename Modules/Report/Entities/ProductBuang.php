<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductBuang extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'vw_product_buang';
}
