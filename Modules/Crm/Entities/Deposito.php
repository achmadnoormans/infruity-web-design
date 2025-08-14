<?php

namespace Modules\Crm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Customer;

class Deposito extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'deposito';

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
