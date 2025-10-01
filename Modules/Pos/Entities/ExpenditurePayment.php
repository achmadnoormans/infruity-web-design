<?php

namespace Modules\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenditurePayment extends Model
{
    use HasFactory;

    protected $table = 'expenditure_payment';

    protected $fillable = [
        'expenditure_id',
        'uuid',
        'nota_number',
        'date',
        'total',
        'payment_method',
        'payment_method_id',
        'payment_amount',
        'branch_id',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Pos\Database\factories\ExpenditurePaymentFactory::new();
    }
}
