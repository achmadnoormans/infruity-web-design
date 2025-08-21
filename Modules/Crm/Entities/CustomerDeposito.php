<?php

namespace Modules\Crm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Customer;

class CustomerDeposito extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'vw_customer_deposito';

    /**
     * Get the tier associated with the customer deposito.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
