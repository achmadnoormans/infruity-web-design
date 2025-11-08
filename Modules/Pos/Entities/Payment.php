<?php

namespace Modules\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Pos\Entities\PosModel;
use Modules\Master\Entities\Branch;
use Modules\Master\Entities\PaymentMethod;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'pos_id',
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
