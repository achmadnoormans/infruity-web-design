<?php

namespace Modules\Crm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportCustomer extends Model
{
    use HasFactory;

    protected $table = 'vw_customer_report';
    protected $fillable = [];
}
