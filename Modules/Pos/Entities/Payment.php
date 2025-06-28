<?php

namespace Modules\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Pos\Entities\PosModel;
use Modules\Master\Entities\Branch;
use Modules\Master\Entities\PaymentMethod;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pos_id',
        'nota_number',
        'date',
        'total',
        'payment_method',
        'branch_id',
        'created_by',
    ];
    protected $table = 'pos_payment';

    public function pos()
    {
        return $this->belongsTo(PosModel::class, 'pos_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method');
    }
}
