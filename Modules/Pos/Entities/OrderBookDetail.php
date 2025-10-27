<?php

namespace Modules\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Pos\Entities\OrderBook;
use Modules\Master\Entities\Product;

class OrderBookDetail extends Model
{
    use HasFactory;

    protected $fillable = ['order_book_id', 'product_id', 'quantity'];
    protected $table = 'order_book_detail';

    public function orderBook()
    {
        return $this->belongsTo(OrderBook::class, 'order_book_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
