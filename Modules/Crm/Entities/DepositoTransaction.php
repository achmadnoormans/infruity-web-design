<?php

namespace Modules\Crm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DepositoTransaction extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'vw_customer_deposito_tansaction';
}
