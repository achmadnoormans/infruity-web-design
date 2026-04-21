<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kurir extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kurir';
    protected $fillable = ['name', 'description', 'type', 'staff_id'];
}