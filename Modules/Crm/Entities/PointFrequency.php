<?php

namespace Modules\Crm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PointFrequency extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'crm_point_frequency';
}
