<?php

namespace Modules\Crm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Crm\Entities\Deposito;
use Modules\Master\Entities\Customer;

class DepositoTransaction extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'vw_customer_deposito_tansaction';

    public function deposito()
    {
        return $this->belongsTo(Deposito::class, 'deposito_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
