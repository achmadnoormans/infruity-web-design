<?php

namespace Modules\Crm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PointSchedule extends Model
{
    use HasFactory;

    protected $table = 'crm_point_schedule';
    protected $fillable = ['start_date', 'end_date', 'frequency', 'created_by', 'updated_by'];
}
