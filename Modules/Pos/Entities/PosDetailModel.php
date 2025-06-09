<?php

namespace Modules\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosDetailModel extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'pos_transaction_detail';

    
}
