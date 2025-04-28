<?php

namespace Modules\Permohonan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenguranganIptBap extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'ipt_pengurangan_bap';    
    
}
