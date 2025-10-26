<?php

namespace Modules\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderBookDetail extends Model
{
    use HasFactory;

    protected $fillable = ['order_book_id', 'product_id', 'quantity'];
    protected $table = 'order_book_detail';
}
