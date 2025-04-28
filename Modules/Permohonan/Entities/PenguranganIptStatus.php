<?php

namespace Modules\Permohonan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenguranganIptStatus extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'm_status_ipt';
	protected $primaryKey = 'id_status';
    
}
