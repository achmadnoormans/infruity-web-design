<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'payment_method';
    
    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\PaymentMethodFactory::new();
    }
}
